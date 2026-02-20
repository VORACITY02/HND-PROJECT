<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function destroy(Message $message)
    {
        $user = Auth::user();

        if ($message->is_broadcast || $message->receiver_id !== $user->id) {
            abort(403, 'You cannot delete this message.');
        }
        if (!$message->is_read) {
            return back()->withErrors(['error' => 'Only read messages can be deleted.']);
        }

        $message->delete();
        return redirect()->route('messages.index')->with('success', 'Message deleted.');
    }

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

    public function index()
    {
        $user = Auth::user();

        $messages = Message::with(['sender', 'receiver'])
            ->where(function($query) use ($user) {
                $query->where('receiver_id', $user->id)
                      ->orWhere(function($q) use ($user) {
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

        $unreadCount = Message::where(function($query) use ($user) {
                $query->where('receiver_id', $user->id)
                      ->where('is_read', false);
            })
            ->orWhere(function($query) use ($user) {
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

    public function create()
    {
        $user = Auth::user();

        $recipients = $this->getAvailableRecipients($user);
        
        return view('messages.create', compact('recipients'));
    }
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

        if ($isBroadcast) {
            if (!$this->canBroadcast($user, $request->recipient_type)) {
                return back()->withErrors(['error' => 'You do not have permission to send broadcast messages to this group.']);
            }
        }

        DB::beginTransaction();
        try {
            if ($isBroadcast) {
                Message::create([
                    'sender_id' => $user->id,
                    'receiver_id' => null,
                    'recipient_type' => $request->recipient_type,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'is_broadcast' => true,
                ]);
            } else {
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

    public function show(Message $message)
    {
        $user = Auth::user();

        if (!$this->canViewMessage($user, $message)) {
            abort(403, 'Unauthorized to view this message.');
        }

        if (($message->receiver_id === $user->id && !$message->is_read) || 
            ($message->is_broadcast && !$message->isReadByUser($user->id))) {
            $message->markAsRead($user->id);
        }

        return view('messages.show', compact('message'));
    }

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

    private function canBroadcast($user, $recipientType)
    {
        if ($user->role === 'admin') {
            return in_array($recipientType, ['all_users', 'all_staff', 'all_admins']);
        } elseif ($user->role === 'staff') {
            return $recipientType === 'all_users';
        }

        return false;
    }

    private function canViewMessage($user, $message)
    {
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

        return $message->sender_id === $user->id || $message->receiver_id === $user->id;
    }
}
