<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    protected $fillable = [
        'user_id',
        'appointed_date',
        
        // Future fields (uncomment when needed):
        // 'department',
        // 'position',
        // 'permissions',
    ];

    protected $casts = [
        'appointed_date' => 'date',
        // 'permissions' => 'array',
    ];

    /**
     * Get the user associated with the admin.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
