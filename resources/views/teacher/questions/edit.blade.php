@extends('layouts.app')

@section('content')

    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-2xl font-semibold text-[#880000] mb-6">Edit Question</h1>

        @if($errors->has('choices'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6 text-sm font-medium">
                {{ $errors->first('choices') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-100 p-6 sm:p-8">
            <form method="POST" action="{{ route('teacher.questions.update', $question) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <select name="subject_id"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm">
                        <option value="">Select a subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $question->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->code }} — {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                    <textarea name="body" rows="3"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm">{{ old('body', $question->body) }}</textarea>
                    @error('body')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <p class="block text-sm font-medium text-gray-700 mb-2">
                        Choices <span class="text-gray-400 font-normal">(select the correct answer)</span>
                    </p>

                    <div class="space-y-3">
                        @foreach($question->choices as $i => $choice)
                            <div class="flex items-center gap-3">
                                <input type="radio"
                                    name="correct_index"
                                    value="{{ $i }}"
                                    {{ old('correct_index', $choice->is_correct ? $i : null) == $i ? 'checked' : '' }}
                                    class="accent-[#880000] w-4 h-4 shrink-0 border-gray-300">
                                <input type="hidden" name="choices[{{ $i }}][is_correct]" value="0">
                                <input type="text"
                                    name="choices[{{ $i }}][body]"
                                    value="{{ old("choices.$i.body", $choice->body) }}"
                                    class="flex-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm">
                            </div>
                            @error("choices.$i.body")
                                <p class="text-red-500 text-xs mt-1 font-medium pl-7">{{ $message }}</p>
                            @enderror
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit"
                        class="inline-flex items-center justify-center bg-[#880000] text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-[#6a0000]">
                        Save Changes
                    </button>
                    <a href="{{ route('teacher.questions.index') }}"
                       class="text-sm font-medium text-gray-500 hover:text-gray-800 hover:underline">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

@endsection