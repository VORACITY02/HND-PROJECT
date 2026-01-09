<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupervisorAssignment extends Model
{
    protected $fillable = [
        'student_id','supervisor_id','assigned_by_admin_id','assigned_at','active'
    ];

    protected $casts = [ 'assigned_at'=>'datetime', 'active'=>'boolean' ];

    public function student(): BelongsTo { return $this->belongsTo(User::class,'student_id'); }
    public function supervisor(): BelongsTo { return $this->belongsTo(User::class,'supervisor_id'); }
    public function assignedBy(): BelongsTo { return $this->belongsTo(User::class,'assigned_by_admin_id'); }
}
