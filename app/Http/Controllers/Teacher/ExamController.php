<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{

    public function index()
    {
        $exams = Exam::with(['subject', 'section'])
            ->where('teacher_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('teacher.exams.index', compact('exams'));
    }

    public function create()
    {
        $subjects = Subject::where('teacher_id', Auth::id())->orderBy('code')->get();
        $sections = Section::with('subject')
            ->whereHas('subject', fn($q) => $q->where('teacher_id', Auth::id()))
            ->orderBy('name')
            ->get();
        $questions = Question::with('subject')
            ->where('teacher_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.exams.create', compact('subjects', 'sections', 'questions'));
    }

    public function store(StoreExamRequest $request)
    {
        $validated = $request->validated();

        $exam = Exam::create([
            'title'            => $validated['title'],
            'subject_id'       => $validated['subject_id'],
            'section_id'       => $validated['section_id'],
            'teacher_id'       => Auth::id(),
            'duration_minutes' => $validated['duration_minutes'],
            'starts_at'        => $validated['starts_at'],
            'ends_at'          => $validated['ends_at'],
            'status'           => 'draft',
        ]);

        // Attach questions with order
        $order = 1;
        foreach ($validated['questions'] as $questionId) {
            $exam->questions()->attach($questionId, ['order' => $order++]);
        }

        Log::info('Teacher created exam.', [
            'teacher_id' => Auth::id(),
            'exam_id' => $exam->id,
            'title' => $exam->title,
            'question_count' => count($validated['questions']),
            'status' => $exam->status,
        ]);

        return redirect()->route('teacher.exams.index')
                         ->with('success', 'Exam created successfully.');
    }

    public function show(Exam $exam)
    {
        $this->authorize('view', $exam);
        $exam->load(['subject', 'section', 'questions.choices']);
        return view('teacher.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        $this->authorize('update', $exam);

        $subjects  = Subject::where('teacher_id', Auth::id())->orderBy('code')->get();
        $sections  = Section::with('subject')
            ->whereHas('subject', fn($q) => $q->where('teacher_id', Auth::id()))
            ->orderBy('name')
            ->get();
        $questions = Question::with('subject')
            ->where('teacher_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedQuestionIds = $exam->questions->pluck('id')->toArray();

        return view('teacher.exams.edit', compact('exam', 'subjects', 'sections', 'questions', 'selectedQuestionIds'));
    }

    public function update(UpdateExamRequest $request, Exam $exam)
    {
        $this->authorize('update', $exam);

        $validated = $request->validated();

        $exam->update([
            'title'            => $validated['title'],
            'subject_id'       => $validated['subject_id'],
            'section_id'       => $validated['section_id'],
            'duration_minutes' => $validated['duration_minutes'],
            'starts_at'        => $validated['starts_at'],
            'ends_at'          => $validated['ends_at'],
        ]);

        // Re-sync questions with order
        $exam->questions()->detach();
        $order = 1;
        foreach ($validated['questions'] as $questionId) {
            $exam->questions()->attach($questionId, ['order' => $order++]);
        }

        Log::info('Teacher updated exam.', [
            'teacher_id' => Auth::id(),
            'exam_id' => $exam->id,
            'title' => $validated['title'],
            'question_count' => count($validated['questions']),
        ]);

        return redirect()->route('teacher.exams.index')
                         ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        $this->authorize('delete', $exam);
        $exam->delete();

        Log::info('Teacher deleted exam.', [
            'teacher_id' => Auth::id(),
            'exam_id' => $exam->id,
        ]);

        return redirect()->route('teacher.exams.index')
                         ->with('success', 'Exam deleted.');
    }

    public function publish(Exam $exam)
    {
        $this->authorize('publish', $exam);

        if ($exam->questions()->count() === 0) {
            return back()->withErrors(['publish' => 'Exam must have at least one question before publishing.']);
        }

        $exam->update(['status' => 'published']);

        Log::info('Teacher published exam.', [
            'teacher_id' => Auth::id(),
            'exam_id' => $exam->id,
            'title' => $exam->title,
        ]);

        return redirect()->route('teacher.exams.index')
                         ->with('success', 'Exam published successfully.');
    }
}