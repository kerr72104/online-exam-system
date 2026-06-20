<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = ['session_id', 'question_id', 'choice_id'];

    public function session()
    {
        return $this->belongsTo(ExamSession::class, 'session_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function choice()
    {
        return $this->belongsTo(Choice::class);
    }

    public function isCorrect(): bool
    {
        return $this->choice?->is_correct ?? false;
    }
}