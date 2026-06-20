<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // As a teacher
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'teacher_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'teacher_id');
    }

    // As a student
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'section_student', 'student_id', 'section_id');
    }

    public function examSessions()
    {
        return $this->hasMany(ExamSession::class, 'student_id');
    }
}