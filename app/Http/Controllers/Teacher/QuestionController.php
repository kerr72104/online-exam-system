<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with(['subject', 'choices'])
            ->where('teacher_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $subjects = Subject::where('teacher_id', Auth::id())->orderBy('code')->get();

        return view('teacher.questions.index', compact('questions', 'subjects'));
    }

    public function create()
    {
        $subjects = Subject::where('teacher_id', Auth::id())->orderBy('code')->get();
        return view('teacher.questions.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body'               => 'required|string',
            'subject_id'         => 'required|exists:subjects,id',
            'choices'            => 'required|array|size:4',
            'choices.*.body'     => 'required|string',
            'choices.*.is_correct' => 'nullable|boolean',
        ]);

        // Ensure exactly one correct answer
        $correctCount = collect($validated['choices'])->filter(
            fn($c) => !empty($c['is_correct'])
        )->count();

        if ($correctCount !== 1) {
            return back()
                ->withInput()
                ->withErrors(['choices' => 'Exactly one correct answer must be selected.']);
        }

        $question = Question::create([
            'body'       => $validated['body'],
            'subject_id' => $validated['subject_id'],
            'teacher_id' => Auth::id(),
        ]);

        foreach ($validated['choices'] as $choice) {
            $question->choices()->create([
                'body'       => $choice['body'],
                'is_correct' => !empty($choice['is_correct']),
            ]);
        }

        return redirect()->route('teacher.questions.index')
                         ->with('success', 'Question created successfully.');
    }

    public function edit(Question $question)
    {
        $this->authorize('update', $question);
        $subjects = Subject::where('teacher_id', Auth::id())->orderBy('code')->get();
        return view('teacher.questions.edit', compact('question', 'subjects'));
    }

    public function update(Request $request, Question $question)
    {
        $this->authorize('update', $question);

        $validated = $request->validate([
            'body'                 => 'required|string',
            'subject_id'           => 'required|exists:subjects,id',
            'choices'              => 'required|array|size:4',
            'choices.*.body'       => 'required|string',
            'choices.*.is_correct' => 'nullable|boolean',
        ]);

        $correctCount = collect($validated['choices'])->filter(
            fn($c) => !empty($c['is_correct'])
        )->count();

        if ($correctCount !== 1) {
            return back()
                ->withInput()
                ->withErrors(['choices' => 'Exactly one correct answer must be selected.']);
        }

        $question->update([
            'body'       => $validated['body'],
            'subject_id' => $validated['subject_id'],
        ]);

        // Delete old choices and recreate
        $question->choices()->delete();
        foreach ($validated['choices'] as $choice) {
            $question->choices()->create([
                'body'       => $choice['body'],
                'is_correct' => !empty($choice['is_correct']),
            ]);
        }

        return redirect()->route('teacher.questions.index')
                         ->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);
        $question->delete();

        return redirect()->route('teacher.questions.index')
                         ->with('success', 'Question deleted.');
    }
}