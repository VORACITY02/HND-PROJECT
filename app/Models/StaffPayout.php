<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffPayout extends Model
{
    protected $fillable = [
        'staff_id',
        'amount_cents',
        'currency',
        'supervisee_count',
        'base_pay_cents',
        'supervisor_fixed_bonus_cents',
        'per_supervisee_bonus_cents',
        'status',
        'external_transfer_id',
        'reference',
        'note',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
