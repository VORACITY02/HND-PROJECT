<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staff extends Model
{
    protected $table = 'staff';
    
    protected $fillable = [
        'user_id',
        'joined_date',
        
        // Future fields (uncomment when needed):
        // 'department',
        // 'position',
        // 'employee_id',
        // 'phone',
        // 'specialization',
    ];

    protected $casts = [
        'joined_date' => 'date',
    ];

    /**
     * Get the user associated with the staff.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
