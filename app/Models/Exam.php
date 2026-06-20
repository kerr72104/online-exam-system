<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'subject_id',
        'section_id',
        'teacher_id',
        'duration_minutes',
        'starts_at',
        'ends_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
        ];
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
                    ->withPivot('order')
                    ->orderByPivot('order');
    }

    public function sessions()
    {
        return $this->hasMany(ExamSession::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'published'
            && now()->between($this->starts_at, $this->ends_at);
    }
}