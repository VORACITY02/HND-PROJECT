<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'recipient_type',
        'subject',
        'message',
        'is_read',
        'is_broadcast',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_broadcast' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the sender of the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the message.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Get the user reads for broadcast messages.
     */
    public function userReads(): HasMany
    {
        return $this->hasMany(MessageUserRead::class);
    }

    /**
     * Scope for unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for messages received by a user.
     */
    public function scopeReceivedBy($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('receiver_id', $userId)
              ->orWhere(function($subQ) use ($userId) {
                  $subQ->where('is_broadcast', true)
                       ->whereHas('sender', function($senderQ) use ($userId) {
                           // Broadcast messages logic
                       });
              });
        });
    }

    /**
     * Mark message as read.
     */
    public function markAsRead($userId = null)
    {
        $userId = $userId ?: auth()->id();
        
        if ($this->is_broadcast) {
            // For broadcast messages, create a read record for this specific user
            MessageUserRead::firstOrCreate([
                'message_id' => $this->id,
                'user_id' => $userId,
            ], [
                'read_at' => now(),
            ]);
        } else {
            // For individual messages, update the message directly
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Check if a message has been read by a specific user.
     */
    public function isReadByUser($userId = null)
    {
        $userId = $userId ?: auth()->id();
        
        if ($this->is_broadcast) {
            return $this->userReads()->where('user_id', $userId)->exists();
        } else {
            return $this->is_read;
        }
    }
}
