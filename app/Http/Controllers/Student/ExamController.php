<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    public function results()
    {
        $session = auth()->user()
            ->examSessions()
            ->with(['exam.questions', 'answers.choice'])
            ->whereNotNull('submitted_at')
            ->whereHas('exam')
            ->latest('submitted_at')
            ->first();

        return view('student.results', compact('session'));
    }

    public function resultShow(ExamSession $session)
    {
        abort_unless($session->student_id === auth()->id(), 403);

        $session->load(['exam.questions.choices', 'answers.choice']);
        abort_if(!$session->exam, 404);

        return view('student.results', compact('session'));
    }

    public function start(Exam $exam)
    {
        ExamSession::firstOrCreate([
            'exam_id' => $exam->id,
            'student_id' => auth()->id(),
        ], [
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        return redirect()->route('student.exams.show', $exam);
    }

    public function show(Exam $exam)
    {
        return view('student.exams.show', compact('exam'));
    }

    public function submit(Request $request, Exam $exam)
    {
        $request->validate([
            'answers' => 'array',
            'answers.*' => 'integer|exists:choices,id',
        ]);

        $studentId = auth()->id();

        $session = ExamSession::firstOrCreate([
            'exam_id' => $exam->id,
            'student_id' => $studentId,
        ], [
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        // store answers
        StudentAnswer::where('session_id', $session->id)->delete();

        $answers = $request->input('answers', []);
        foreach ($exam->questions as $question) {
            if (! isset($answers[$question->id])) {
                continue;
            }

            StudentAnswer::create([
                'session_id' => $session->id,
                'question_id' => $question->id,
                'choice_id' => $answers[$question->id],
            ]);
        }

        $session->refresh();
        $session->load('answers.choice', 'exam.questions');
        $session->grade();

        return redirect()->route('student.results');
    }
}
