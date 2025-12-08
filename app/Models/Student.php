<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'enrollment_date',
        
        // Future fields (uncomment when needed):
        // 'student_id',
        // 'phone',
        // 'university',
        // 'major',
        // 'year_of_study',
        // 'gpa',
        // 'skills',
        // 'bio',
        // 'resume_path',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        // 'gpa' => 'decimal:2',
    ];

    /**
     * Get the user associated with the student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
