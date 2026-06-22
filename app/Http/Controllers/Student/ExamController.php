<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function results()
    {
        return view('student.results');
    }

    public function show(Exam $exam)
    {
        return view('student.exams.show', compact('exam'));
    }

    public function resultShow(ExamSession $session)
    {
        // Ensure the session belongs to the authenticated student
        if (Auth::id() !== $session->student_id) {
            abort(403);
        }

        $session->load(['exam.questions.choices', 'answers.choice', 'answers.question']);

        return view('student.results.show', compact('session'));
    }
}
