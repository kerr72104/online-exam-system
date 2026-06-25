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
        abort_unless($exam->isAvailable(), 403);

        $studentId = auth()->id();

        $session = ExamSession::where('exam_id', $exam->id)
            ->where('student_id', $studentId)
            ->first();

        if (! $session) {
            $session = ExamSession::create([
                'exam_id' => $exam->id,
                'student_id' => $studentId,
                'started_at' => now(),
                'status' => 'in_progress',
            ]);
        }

        return redirect()->route('student.exams.show', $exam);
    }

    public function show(Exam $exam)
    {
        abort_unless($exam->isAvailable(), 403);

        $studentId = auth()->id();

        $session = ExamSession::where('exam_id', $exam->id)
            ->where('student_id', $studentId)
            ->first();

        if (! $session) {
            $session = ExamSession::create([
                'exam_id' => $exam->id,
                'student_id' => $studentId,
                'started_at' => now(),
                'status' => 'in_progress',
            ]);
        }

        // If session already submitted, redirect to results
        if ($session->submitted_at) {
            return redirect()->route('student.results.show', $session);
        }

        $session->load('answers');

        $answersMap = $session->answers->pluck('choice_id', 'question_id')->toArray();

        $remaining = $session->timeRemaining();

        return view('student.exams.show', compact('exam', 'session', 'answersMap', 'remaining'));
    }

    public function status(Exam $exam)
    {
        $studentId = auth()->id();

        $session = ExamSession::where('exam_id', $exam->id)
            ->where('student_id', $studentId)
            ->first();

        if (! $session) {
            return response()->json([
                'remaining' => $exam->duration_minutes * 60,
                'answers' => [],
                'server_time' => now()->timestamp,
            ]);
        }

        $answers = $session->answers()->pluck('choice_id', 'question_id')->toArray();

        return response()->json([
            'remaining' => $session->timeRemaining(),
            'answers' => $answers,
            'server_time' => now()->timestamp,
        ]);
    }

    public function autosave(Request $request, Exam $exam)
    {
        $studentId = auth()->id();

        $session = ExamSession::firstOrCreate([
            'exam_id' => $exam->id,
            'student_id' => $studentId,
        ], [
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        if ($session->student_id !== $studentId) {
            abort(403);
        }

        // If time has expired, grade immediately
        if ($session->timeRemaining() <= 0) {
            $session->load('answers.choice', 'exam.questions');
            $session->grade();
            return response()->json(['status' => 'expired', 'remaining' => 0]);
        }

        // Accept JSON body, form-encoded, or raw beacon payload
        $answers = $request->input('answers', null);
        if (is_null($answers)) {
            $content = $request->getContent();
            if ($content) {
                $data = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($data['answers'])) {
                    $answers = $data['answers'];
                }
            }
        }

        // Only replace answers when answers are provided (avoid deleting on empty beacon)
        if (is_array($answers) && count($answers) > 0) {
            \App\Models\StudentAnswer::where('session_id', $session->id)->delete();

            foreach ($answers as $questionId => $choiceId) {
                \App\Models\StudentAnswer::create([
                    'session_id' => $session->id,
                    'question_id' => $questionId,
                    'choice_id' => $choiceId,
                ]);
            }
        }

        return response()->json(['status' => 'ok', 'remaining' => $session->timeRemaining()]);
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
