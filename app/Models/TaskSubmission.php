<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskSubmission extends Model
{
    protected $fillable = [ 'task_id','student_id','submitted_at','file_path','content','grade_score','graded_at','status' ];
    protected $casts = [ 'submitted_at'=>'datetime', 'graded_at'=>'datetime' ];

    public function task(): BelongsTo { return $this->belongsTo(InternshipTask::class,'task_id'); }
    public function student(): BelongsTo { return $this->belongsTo(User::class,'student_id'); }
}
