<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function store(StoreQuestionRequest $request)
    {
        $validated = $request->validated();

        $question = Question::create([
            'body'       => $validated['body'],
            'subject_id' => $validated['subject_id'],
            'teacher_id' => Auth::id(),
        ]);

        foreach ($validated['choices'] as $index => $choice) {
            $question->choices()->create([
                'body'       => $choice['body'],
                // If the current loop index matches the radio button value, it's correct
                'is_correct' => $index === (int) $validated['correct_index'],
            ]);
        }

        Log::info('Teacher created question.', [
            'teacher_id' => Auth::id(),
            'question_id' => $question->id,
            'subject_id' => $validated['subject_id'],
            'choice_count' => count($validated['choices']),
        ]);

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

        $validated = $request->validated();

        $question->update([
            'body'       => $validated['body'],
            'subject_id' => $validated['subject_id'],
        ]);

        // Delete old choices and recreate
        $question->choices()->delete();
        foreach ($validated['choices'] as $index => $choice) {
            $question->choices()->create([
                'body'       => $choice['body'],
                'is_correct' => $index === (int) $validated['correct_index'],
            ]);
        }

        Log::info('Teacher updated question.', [
            'teacher_id' => Auth::id(),
            'question_id' => $question->id,
            'subject_id' => $validated['subject_id'],
            'choice_count' => count($validated['choices']),
        ]);

        return redirect()->route('teacher.questions.index')
                         ->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);
        $question->delete();

        Log::info('Teacher deleted question.', [
            'teacher_id' => Auth::id(),
            'question_id' => $question->id,
        ]);

        return redirect()->route('teacher.questions.index')
                         ->with('success', 'Question deleted.');
    }
}