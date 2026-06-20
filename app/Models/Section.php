<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['name', 'subject_id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'section_student', 'section_id', 'student_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}