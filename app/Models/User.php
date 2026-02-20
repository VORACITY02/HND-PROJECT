<?php

namespace App\Models;

use App\Models\PaymentAccount;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'last_seen_at',
        'is_online',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
            'is_online' => 'boolean',
        ];
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function roleProfile()
    {
        return match($this->role) {
            'admin' => $this->admin,
            'staff' => $this->staff,
            'user' => $this->student,
            default => null,
        };
    }

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function isOnline()
    {
        return $this->last_seen_at && 
               $this->last_seen_at->gt(now()->subMinutes(2));
    }

    public function updateLastSeen()
    {
        $this->update([
            'last_seen_at' => now(),
            'is_online' => true,
        ]);
    }

    public function scopeOnline($query)
    {
        return $query->where('last_seen_at', '>=', now()->subMinutes(2))
                    ->where('last_seen_at', '<=', now());
    }
    public function SupervisorApplication(){
        return $this->hasOne(SupervisorApplication::class,'staff_id');
    }

    public function personalData()
    {
        return $this->hasOne(PersonalData::class);
    }

    public function paymentAccount()
    {
        return $this->hasOne(PaymentAccount::class);
    }
}

