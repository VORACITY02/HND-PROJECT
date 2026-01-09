<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Delete a single read direct message received by the current user.
     */
    public function destroy(Message $message)
    {
        $user = Auth::user();

        // Only allow deleting direct messages that the user received and have been read
        if ($message->is_broadcast || $message->receiver_id !== $user->id) {
            abort(403, 'You cannot delete this message.');
        }
        if (!$message->is_read) {
            return back()->withErrors(['error' => 'Only read messages can be deleted.']);
        }

        $message->delete();
        return redirect()->route('messages.index')->with('success', 'Message deleted.');
    }

    /**
     * Bulk delete selected read direct messages received by the current user.
     */
    public function bulkDestroy(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'integer',
        ]);

        $count = Message::whereIn('id', $request->message_ids)
            ->where('receiver_id', $user->id)
            ->where('is_broadcast', false)
            ->where('is_read', true)
            ->delete();

        return redirect()->route('messages.index')->with('success', $count . ' message(s) deleted.');
    }
    /**
     * Display the message center.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get inbox messages
        $messages = Message::with(['sender', 'receiver'])
            ->where(function($query) use ($user) {
                $query->where('receiver_id', $user->id)
                      ->orWhere(function($q) use ($user) {
                          // Broadcast messages for this user's role
                          $q->where('is_broadcast', true)
                            ->where(function($subQ) use ($user) {
                                if ($user->role === 'user') {
                                    $subQ->where('recipient_type', 'all_users');
                                } elseif ($user->role === 'staff') {
                                    $subQ->where('recipient_type', 'all_staff');
                                } elseif ($user->role === 'admin') {
                                    $subQ->where('recipient_type', 'all_admins');
                                }
                            });
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get unread count
        $unreadCount = Message::where(function($query) use ($user) {
                // Individual messages to this user that are unread
                $query->where('receiver_id', $user->id)
                      ->where('is_read', false);
            })
            ->orWhere(function($query) use ($user) {
                // Broadcast messages for this user's role that they haven't read
                $query->where('is_broadcast', true)
                      ->where(function($subQ) use ($user) {
                          if ($user->role === 'user') {
                              $subQ->where('recipient_type', 'all_users');
                          } elseif ($user->role === 'staff') {
                              $subQ->where('recipient_type', 'all_staff');
                          } elseif ($user->role === 'admin') {
                              $subQ->where('recipient_type', 'all_admins');
                          }
                      })
                      ->whereDoesntHave('userReads', function($subQ) use ($user) {
                          $subQ->where('user_id', $user->id);
                      });
            })
            ->count();

        return view('messages.index', compact('messages', 'unreadCount'));
    }

    /**
     * Show the compose message form.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get users that can be messaged based on role
        $recipients = $this->getAvailableRecipients($user);
        
        return view('messages.create', compact('recipients'));
    }

    /**
     * Store a new message.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'recipient_type' => 'required|string',
            'receiver_id' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $isBroadcast = in_array($request->recipient_type, ['all_users', 'all_staff', 'all_admins']);

        // Check permissions for broadcast
        if ($isBroadcast) {
            if (!$this->canBroadcast($user, $request->recipient_type)) {
                return back()->withErrors(['error' => 'You do not have permission to send broadcast messages to this group.']);
            }
        }

        // No restrictions on individual messaging
        // Users can message anyone for discussions and collaboration

        DB::beginTransaction();
        try {
            if ($isBroadcast) {
                // Create broadcast message
                Message::create([
                    'sender_id' => $user->id,
                    'receiver_id' => null,
                    'recipient_type' => $request->recipient_type,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'is_broadcast' => true,
                ]);
            } else {
                // Create individual message
                Message::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $request->receiver_id,
                    'recipient_type' => 'individual',
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'is_broadcast' => false,
                ]);
            }

            DB::commit();

            return redirect()->route('messages.index')
                ->with('success', 'Message sent successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to send message: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show a specific message.
     */
    public function show(Message $message)
    {
        $user = Auth::user();

        // Check if user has permission to view this message
        if (!$this->canViewMessage($user, $message)) {
            abort(403, 'Unauthorized to view this message.');
        }

        // Mark as read for both individual and broadcast messages
        if (($message->receiver_id === $user->id && !$message->is_read) || 
            ($message->is_broadcast && !$message->isReadByUser($user->id))) {
            $message->markAsRead($user->id);
        }

        return view('messages.show', compact('message'));
    }

    /**
     * Get sent messages.
     */
    public function sent()
    {
        $messages = Auth::user()->sentMessages()
            ->with(['receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('messages.sent', compact('messages'));
    }

    /**
     * Get online users.
     */
    public function onlineUsers()
    {
        $user = Auth::user();
        
        $onlineUsers = User::online()
            ->where('id', '!=', $user->id)
            ->when($user->role === 'user', function($query) {
                // Users can see other users and staff
                $query->whereIn('role', ['user', 'staff']);
            })
            ->when($user->role === 'staff', function($query) {
                // Staff can see users, staff, and admins
                $query->whereIn('role', ['user', 'staff', 'admin']);
            })
            ->select('id', 'name', 'email', 'role', 'last_seen_at', 'is_online')
            ->get();

        return response()->json([
            'online_users' => $onlineUsers,
            'count' => $onlineUsers->count(),
        ]);
    }

    /**
     * Get available recipients based on user role.
     */
    private function getAvailableRecipients($user)
    {
        $query = User::where('id', '!=', $user->id);

        if ($user->role === 'user') {
            // Users can message everyone (staff, admins, and other users)
            $query->whereIn('role', ['user', 'staff', 'admin']);
        } elseif ($user->role === 'staff') {
            // Staff can message everyone
            $query->whereIn('role', ['user', 'staff', 'admin']);
        } elseif ($user->role === 'admin') {
            // Admins can message everyone
            $query->whereIn('role', ['user', 'staff', 'admin']);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Check if user can broadcast to a specific group.
     */
    private function canBroadcast($user, $recipientType)
    {
        if ($user->role === 'admin') {
            // Admins can broadcast to everyone
            return in_array($recipientType, ['all_users', 'all_staff', 'all_admins']);
        } elseif ($user->role === 'staff') {
            // Staff can only broadcast to all users
            return $recipientType === 'all_users';
        }

        return false;
    }

    /**
     * Check if user can view a message.
     */
    private function canViewMessage($user, $message)
    {
        // If it's a broadcast message
        if ($message->is_broadcast) {
            if ($message->recipient_type === 'all_users' && $user->role === 'user') {
                return true;
            }
            if ($message->recipient_type === 'all_staff' && $user->role === 'staff') {
                return true;
            }
            if ($message->recipient_type === 'all_admins' && $user->role === 'admin') {
                return true;
            }
        }

        // If it's a direct message
        return $message->sender_id === $user->id || $message->receiver_id === $user->id;
    }
}
