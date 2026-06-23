<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $student */
        $student = Auth::user();
        $sectionIds = $student->sections()->pluck('id');

        $upcomingExams = Exam::with(['subject', 'section', 'teacher'])
            ->whereIn('section_id', $sectionIds)
            ->where('status', 'published')
            ->where('ends_at', '>=', now())
            ->orderBy('starts_at')
            ->limit(8)
            ->get();

        $assignedCount = Exam::whereIn('section_id', $sectionIds)
            ->where('status', 'published')
            ->count();

        $recentSessions = $student->examSessions()
            ->with('exam.subject', 'exam.section')
            ->whereNotNull('submitted_at')
            ->orderBy('submitted_at', 'desc')
            ->limit(4)
            ->get();

        $scoresByExam = $student->examSessions()
            ->whereNotNull('submitted_at')
            ->pluck('score', 'exam_id');

        $classes = $student->sections()->with('subject')->orderBy('name')->get();

        return view('student.dashboard', compact(
            'student',
            'upcomingExams',
            'assignedCount',
            'recentSessions',
            'classes',
            'scoresByExam'
        ));
    }
}
