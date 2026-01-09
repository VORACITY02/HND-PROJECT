<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternshipTask extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'supervisor_id','assigned_student_id','title','description','due_at','max_score','active','is_special' ];
    protected $casts = [ 'due_at'=>'datetime', 'active'=>'boolean', 'is_special'=>'boolean' ];

    public function supervisor(): BelongsTo { return $this->belongsTo(User::class,'supervisor_id'); }
    public function assignedStudent(): BelongsTo { return $this->belongsTo(User::class,'assigned_student_id'); }
    public function submissions(): HasMany { return $this->hasMany(TaskSubmission::class,'task_id'); }
}
