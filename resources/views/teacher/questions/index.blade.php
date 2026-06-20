@extends('layouts.app')

@section('content')

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <h1 class="text-2xl font-semibold text-[#880000]">Question Bank</h1>
            <a href="{{ route('teacher.questions.create') }}"
               class="inline-flex items-center gap-1.5 bg-[#880000] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-[#6a0000]">
                + Add Question
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- Subject Filter --}}
        <div class="mb-4">
            <form method="GET" action="{{ route('teacher.questions.index') }}">
                <select name="subject_id" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#880000]/20 focus:border-[#880000]">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->code }} — {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left whitespace-nowrap min-w-[700px]">
                    <thead class="bg-[#880000] border-b-2 border-[#880000] uppercase text-xs text-white font-medium tracking-wide">
                        <tr>
                            <th class="px-5 py-3 w-1/2">Question</th>
                            <th class="px-5 py-3">Subject</th>
                            <th class="px-5 py-3">Choices</th>
                            <th class="px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($questions as $question)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3.5 text-gray-800 text-sm whitespace-normal min-w-[300px]">
                                    {{ Str::limit($question->body, 80) }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-mono bg-gray-100 text-gray-500 px-2 py-0.5 rounded text-xs">
                                        {{ $question->subject->code ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 text-xs">
                                    {{ $question->choices->count() }} choices
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('teacher.questions.edit', $question) }}"
                                           class="text-[#880000] text-xs font-medium hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('teacher.questions.destroy', $question) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this question?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 text-xs font-medium hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-gray-400 text-sm">
                                    No questions yet. Add your first question.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($questions->hasPages())
                <div class="px-5 py-4 border-t bg-gray-50">
                    {{ $questions->links() }}
                </div>
            @endif
        </div>

    </div>

@endsection