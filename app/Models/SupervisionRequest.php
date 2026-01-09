<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupervisionRequest extends Model
{
    protected $fillable = [
        'student_id','requested_supervisor_id','requested_admin_id','status','note'
    ];

    public function student(): BelongsTo { return $this->belongsTo(User::class,'student_id'); }
    public function requestedSupervisor(): BelongsTo { return $this->belongsTo(User::class,'requested_supervisor_id'); }
    public function requestedAdmin(): BelongsTo { return $this->belongsTo(User::class,'requested_admin_id'); }
}
