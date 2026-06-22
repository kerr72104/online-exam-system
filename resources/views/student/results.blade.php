@extends('layouts.app')
@section('title', 'Student Exam Results')
@section('content')
<div class="bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if (!isset($session))
            <div class="rounded-3xl bg-white shadow-lg p-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900">No exam results available</h1>
                <p class="mt-4 text-gray-600">You have not submitted an exam yet.</p>
                <a href="{{ route('student.dashboard') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-[#880000] text-white font-bold px-6 py-3 hover:bg-[#660000] transition-colors">Back to dashboard</a>
            </div>
        @else
        <div class="bg-[#880000] text-white rounded-3xl shadow-lg p-8">
            <div class="flex items-center justify-between gap-6 flex-col sm:flex-row">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-white/75">Exam Submitted</p>
                    <h1 class="mt-3 text-4xl font-extrabold">Your score is ready</h1>
                </div>
                <div class="rounded-3xl bg-white/10 p-6 text-center">
                    <p class="text-sm uppercase text-white/70">Final Score</p>
                    <p class="mt-3 text-5xl font-black text-white">{{ $session->score ?? 0 }} / {{ $session->exam->questions->count() }}</p>
                    <p class="text-sm text-white/70 mt-1">{{ $session->score && $session->exam->questions->count() > 0 ? round(($session->score / $session->exam->questions->count()) * 100) : 0 }}%</p>
                </div>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <div class="rounded-3xl bg-white/10 p-6">
                    <p class="text-xs uppercase tracking-[0.28em] text-white/70">Exam</p>
                    <p class="mt-3 text-xl font-semibold">{{ $session->exam->title }}</p>
                    <p class="text-sm text-white/75">{{ $session->exam->subject->name ?? 'Subject' }}</p>
                </div>
                <div class="rounded-3xl bg-white/10 p-6">
                    <p class="text-xs uppercase tracking-[0.28em] text-white/70">Submitted</p>
                    <p class="mt-3 text-xl font-semibold">{{ $session->submitted_at?->format('M d, Y') ?? '—' }}</p>
                    <p class="text-sm text-white/75">{{ $session->submitted_at?->format('h:i A') ?? '—' }}</p>
                </div>
            </div>

            <div class="mt-8 rounded-3xl bg-white/10 p-6 text-white/90">
                <h2 class="text-xl font-bold">What you answered</h2>
                <div class="mt-4 space-y-4">
                    @foreach($session->exam->questions as $index => $question)
                        @php
                            $answer = $session->answers->firstWhere('question_id', $question->id);
                            $selected = $answer?->choice;
                        @endphp
                        <div class="rounded-2xl bg-white/10 p-4 border border-white/10">
                            <div class="flex justify-between items-start gap-4">
                                <div>
                                    <p class="text-sm font-semibold">Question {{ $index + 1 }}</p>
                                    <p class="mt-2 text-sm">{{ $question->body }}</p>
                                </div>
                                <span class="text-xs uppercase tracking-[0.28em] text-white/70">{{ $selected?->is_correct ? 'Correct' : 'Incorrect' }}</span>
                            </div>
                            <div class="mt-4 text-sm">
                                <p><span class="font-semibold">Your answer:</span> {{ $selected->body ?? 'No answer' }}</p>
                                <p class="mt-2"><span class="font-semibold">Correct answer:</span> {{ $question->choices->firstWhere('is_correct', 1)?->body ?? 'Not available' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <a href="{{ route('student.dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-white text-[#880000] font-bold px-6 py-3 hover:bg-gray-100 transition-colors">Back to dashboard</a>
                <a href="{{ route('student.exams.show', $session->exam) }}" class="inline-flex items-center justify-center rounded-full border border-white/30 text-white px-6 py-3 hover:bg-white/10 transition-colors">Review exam</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection