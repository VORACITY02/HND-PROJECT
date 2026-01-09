<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalData extends Model
{
    protected $table = 'personal_data';

    protected $fillable = [
        'user_id',
        'department',
        'title',
        'phone',
        'address',
        'bio',
        'extras',
    ];

    protected $casts = [
        'extras' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}