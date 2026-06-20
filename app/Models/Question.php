<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = ['body', 'subject_id', 'teacher_id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }

    public function correctChoice()
    {
        return $this->hasOne(Choice::class)->where('is_correct', true);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_questions')->withPivot('order');
    }
}