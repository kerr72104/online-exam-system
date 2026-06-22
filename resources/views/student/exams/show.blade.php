@extends('layouts.app')
@section('title', $exam->title)
@section('content')
<div class="bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Exam Header -->
        <div class="mb-8 pb-6 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-900">{{ $exam->title }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $exam->subject->name ?? 'Subject' }}</p>
        </div>

        <!-- Exam Form -->
        <form action="{{ route('student.exams.submit', $exam) }}" method="POST" id="examForm">
            @csrf

            <!-- Questions -->
            @forelse($exam->questions as $index => $question)
                <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                    <!-- Question Number and Text -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <span class="text-[#880000]">Question {{ $index + 1 }}.</span> {{ $question->body }}
                        </h3>
                    </div>

                    <!-- Multiple Choice Options -->
                    <fieldset class="space-y-3">
                        @forelse($question->choices as $choice)
                            <label class="flex items-start p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-white transition-colors" >
                                <input 
                                    type="radio" 
                                    name="answers[{{ $question->id }}]" 
                                    value="{{ $choice->id }}"
                                    class="mt-1 h-4 w-4 text-[#880000] cursor-pointer"
                                >
                                <span class="ml-3 text-gray-700">{{ $choice->body }}</span>
                            </label>
                        @empty
                            <p class="text-gray-500 text-sm">No options available</p>
                        @endforelse
                    </fieldset>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500">No questions available for this exam.</p>
                </div>
            @endforelse

            <!-- Submit Button -->
            @if($exam->questions->count() > 0)
                <div class="sticky bottom-0 bg-white border-t border-gray-200 p-4 mt-8 flex justify-between">
                    <button type="button" onclick="window.history.back()" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                        Back
                    </button>
                    <button type="submit" class="px-8 py-3 bg-[#880000] text-white font-bold rounded-lg hover:bg-[#660000] transition-colors">
                        Submit Exam
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection