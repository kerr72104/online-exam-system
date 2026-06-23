<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::with(['subject', 'students'])->orderBy('name')->paginate(15);
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        $subjects = Subject::with('teacher')->orderBy('code')->get();
        $students = User::where('role', 'student')->orderBy('name')->get();
        return view('admin.sections.create', compact('subjects', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'students'   => 'nullable|array',
            'students.*' => 'exists:users,id',
        ]);

        $section = Section::create([
            'name'       => $validated['name'],
            'subject_id' => $validated['subject_id'],
        ]);

        if (!empty($validated['students'])) {
            $section->students()->sync($validated['students']);
        }

        Log::info('Admin created section.', [
            'section_id' => $section->id,
            'name' => $section->name,
            'subject_id' => $section->subject_id,
            'student_count' => count($validated['students'] ?? []),
        ]);

        return redirect()->route('admin.sections.index')
                         ->with('success', 'Section created successfully.');
    }

    public function edit(Section $section)
    {
        $subjects = Subject::with('teacher')->orderBy('code')->get();
        $students = User::where('role', 'student')->orderBy('name')->get();
        $enrolledIds = $section->students->pluck('id')->toArray();
        return view('admin.sections.edit', compact('section', 'subjects', 'students', 'enrolledIds'));
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'students'   => 'nullable|array',
            'students.*' => 'exists:users,id',
        ]);

        $section->update([
            'name'       => $validated['name'],
            'subject_id' => $validated['subject_id'],
        ]);

        $section->students()->sync($validated['students'] ?? []);

        Log::info('Admin updated section.', [
            'section_id' => $section->id,
            'name' => $validated['name'],
            'subject_id' => $validated['subject_id'],
            'student_count' => count($validated['students'] ?? []),
        ]);

        return redirect()->route('admin.sections.index')
                         ->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        $section->delete();

        Log::info('Admin deleted section.', [
            'section_id' => $section->id,
            'name' => $section->name,
        ]);

        return redirect()->route('admin.sections.index')
                         ->with('success', 'Section deleted.');
    }
}