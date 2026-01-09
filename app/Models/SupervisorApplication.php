<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupervisorApplication extends Model
{
   protected $fillable=[
    'staff_id',
    'max_students',
    'status',
    'admin_note',
   ];
   public function staff()
   {
   return $this->belongsTo(User::class, 'staff_id');
   }
}
