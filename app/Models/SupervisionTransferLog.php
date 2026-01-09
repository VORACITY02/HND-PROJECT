<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupervisionTransferLog extends Model
{
    protected $fillable = [
        'student_id',
        'from_supervisor_id',
        'to_supervisor_id',
        'performed_by_admin_id',
        'transferred_at',
        'notes',
    ];

    protected $casts = [
        'transferred_at' => 'datetime',
    ];

    public function student(): BelongsTo { return $this->belongsTo(User::class, 'student_id'); }
    public function fromSupervisor(): BelongsTo { return $this->belongsTo(User::class, 'from_supervisor_id'); }
    public function toSupervisor(): BelongsTo { return $this->belongsTo(User::class, 'to_supervisor_id'); }
    public function performedBy(): BelongsTo { return $this->belongsTo(User::class, 'performed_by_admin_id'); }
}
