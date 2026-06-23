<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ResultController extends Controller
{
    public function index()
    {
        $exams = Exam::with(['subject', 'section', 'sessions'])
            ->where('teacher_id', Auth::id())
            ->whereIn('status', ['published', 'closed'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($exam) {
                $exam->total_students   = $exam->section?->students()->count() ?? 0;
                $exam->submitted_count  = $exam->sessions->whereIn('status', ['submitted', 'graded'])->count();
                $exam->avg_score        = $exam->sessions->whereNotNull('score')->avg('score');
                $exam->highest_score    = $exam->sessions->whereNotNull('score')->max('score');
                $exam->lowest_score     = $exam->sessions->whereNotNull('score')->min('score');
                $exam->question_count   = $exam->questions()->count();
                return $exam;
            });

        return view('teacher.results.index', compact('exams'));
    }

    public function show(Exam $exam)
    {
        abort_if($exam->teacher_id !== Auth::id(), 403);

        $exam->load(['subject', 'section', 'questions.choices']);

        $sessions = ExamSession::with(['student', 'answers.choice', 'answers.question'])
            ->where('exam_id', $exam->id)
            ->whereIn('status', ['submitted', 'graded'])
            ->orderBy('score', 'desc')
            ->get();

        $questionCount = $exam->questions->count();

        $stats = [
            'total_students'  => $exam->section?->students()->count() ?? 0,
            'submitted_count' => $sessions->count(),
            'avg_score'       => round($sessions->avg('score'), 1),
            'highest_score'   => $sessions->max('score'),
            'lowest_score'    => $sessions->min('score'),
        ];

        return view('teacher.results.show', compact('exam', 'sessions', 'stats', 'questionCount'));
    }
}