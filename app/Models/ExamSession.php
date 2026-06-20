<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'started_at',
        'submitted_at',
        'score',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at'   => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class, 'session_id');
    }

    public function grade(): void
    {
        $correct = $this->answers->filter(
            fn($answer) => $answer->choice?->is_correct
        )->count();

        $this->update([
            'score'        => $correct,
            'status'       => 'graded',
            'submitted_at' => now(),
        ]);
    }

    public function timeRemaining(): int
    {
        $elapsed = now()->diffInSeconds($this->started_at);
        $total   = $this->exam->duration_minutes * 60;

        return max(0, $total - $elapsed);
    }
}