<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'last_seen_at',
        'is_online',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
            'is_online' => 'boolean',
        ];
    }

    /**
     * Get the admin profile associated with the user.
     */
    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Get the staff profile associated with the user.
     */
    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    /**
     * Get the student profile associated with the user.
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get the role-specific profile.
     */
    public function roleProfile()
    {
        return match($this->role) {
            'admin' => $this->admin,
            'staff' => $this->staff,
            'user' => $this->student,
            default => null,
        };
    }
    //for the profile
    public function profile(){
        return $this->hasOne(Profile::class);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is staff.
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user is regular user.
     */
    public function isUser()
    {
        return $this->role === 'user';
    }

    /**
     * Get sent messages.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get received messages.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Check if user is online (active in last 2 minutes for real-time detection).
     */
    public function isOnline()
    {
        return $this->last_seen_at && 
               $this->last_seen_at->gt(now()->subMinutes(2));
    }

    /**
     * Update last seen timestamp.
     */
    public function updateLastSeen()
    {
        $this->update([
            'last_seen_at' => now(),
            'is_online' => true,
        ]);
    }

    /**
     * Scope for online users (active within last 2 minutes for better real-time detection).
     */
    public function scopeOnline($query)
    {
        return $query->where('last_seen_at', '>=', now()->subMinutes(2))
                    ->where('last_seen_at', '<=', now()); // Ensure not future dates
    }
}
