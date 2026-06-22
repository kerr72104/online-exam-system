@extends('layouts.app')

@section('title', 'Exam Result')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#880000]">{{ $session->exam->title ?? 'Exam Result' }}</h1>
        <p class="text-sm text-gray-600 mt-1">{{ $session->exam->subject->code ?? '' }} • {{ $session->exam->section->name ?? '' }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Submitted</p>
                <p class="font-medium">{{ optional($session->submitted_at)->format('M d, Y h:i A') }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Score</p>
                <p class="text-2xl font-bold text-[#880000]">{{ $session->score ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @php
            $answers = $session->answers->keyBy('question_id');
        @endphp

        @foreach($session->exam->questions as $i => $question)
            @php
                $userAnswer = $answers->get($question->id);
                $selectedChoiceId = $userAnswer->choice_id ?? null;
            @endphp

            <div class="bg-white rounded-xl border border-gray-100 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <p class="text-sm text-gray-500">Question {{ $i + 1 }}</p>
                        <p class="mt-2 text-lg font-medium text-gray-900">{{ $question->body }}</p>
                        <div class="mt-4 space-y-2">
                            @foreach($question->choices as $choice)
                                @php
                                    $isSelected = $selectedChoiceId == $choice->id;
                                    $isCorrect = (bool) $choice->is_correct;
                                @endphp

                                <div class="flex items-start gap-3 p-3 rounded-lg border
                                    @if($isCorrect) bg-green-50 border-green-200 text-green-700 font-medium
                                    @elseif($isSelected) bg-red-50 border-red-200 text-red-700
                                    @else bg-gray-50 border-transparent text-gray-700
                                    @endif">
                                    <div class="w-5 h-5 flex items-center justify-center rounded-full border">
                                        @if($isCorrect)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-700" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15l-4.121-4.121a1 1 0 011.414-1.414L8.414 12.172l7.879-7.879a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        @elseif($isSelected)
                                            <span class="text-xs font-semibold text-red-700">✓</span>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm">{{ $choice->body }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        <a href="{{ route('student.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-[#880000]">← Back to dashboard</a>
    </div>
</div>
@endsection
