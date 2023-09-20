<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollCourse extends Model
{
    use HasFactory;

    protected $table        = 'enroll_courses';

    protected $primaryKey   = 'enroll_course_id';

    public function course() {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }
}
