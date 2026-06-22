<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

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
}
