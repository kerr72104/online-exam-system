@extends('layouts.app')

@section('title', 'My Classroom')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 h-screen flex flex-col">
    <div class="flex flex-col gap-6 flex-1 min-h-0">
        
        <section class="flex-shrink-0 rounded-[32px] border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#880000]">Welcome back</p>
                    <h1 class="mt-3 text-3xl font-semibold text-gray-900">Hi, {{ $student->name }}</h1>
                    <p class="mt-3 text-sm text-gray-600 max-w-2xl">This is your student workspace. Track your upcoming exams, completed assessments, and enrolled classes from one place.</p>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="rounded-3xl bg-[#880000] p-5 text-white">
                        <p class="text-xs uppercase tracking-[0.2em] text-white/70">Classes</p>
                        <p class="mt-4 text-3xl font-semibold">{{ $classes->count() }}</p>
                        <p class="mt-2 text-sm text-white/80">Enrolled sections</p>
                    </div>
                    <div class="rounded-3xl border border-gray-200 bg-white p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Assigned</p>
                        <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $assignedCount }}</p>
                        <p class="mt-2 text-sm text-gray-500">Published exams</p>
                    </div>
                    <div class="rounded-3xl border border-gray-200 bg-white p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Completed</p>
                        <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $recentSessions->count() }}</p>
                        <p class="mt-2 text-sm text-gray-500">Finished exams</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-[1.7fr_0.95fr] gap-6 lg:gap-12 flex-1 min-h-0">
            
            <section class="space-y-8 lg:border-r lg:border-gray-200 lg:pr-12 lg:overflow-y-auto lg:max-h-[calc(100vh-280px)] pr-2">
                
                <div class="space-y-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">To Do</h2>
                        <p class="mt-1 text-sm text-gray-500">Your active exams and upcoming assessments.</p>
                    </div>

                    <div class="max-h-[400px] overflow-y-auto pr-2 space-y-4">
                        @forelse($upcomingExams as $exam)
                            @php
                                $isOpen = now()->between($exam->starts_at, $exam->ends_at);
                                $startsSoon = now()->lt($exam->starts_at);
                                $score = $scoresByExam[$exam->id] ?? null;
                            @endphp

                            <article class="rounded-[28px] border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="space-y-3">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center rounded-full bg-[#880000] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">{{ $exam->subject->code ?? 'CLASS' }}</span>
                                            <span class="text-sm text-gray-500">{{ $exam->section->name ?? 'Section' }}</span>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $exam->title }}</h3>
                                        <p class="text-sm text-gray-600">{{ $exam->subject->name ?? 'No subject name' }}</p>
                                    </div>

                                    <div class="flex flex-col items-start gap-2 sm:items-end">
                                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $isOpen ? 'bg-green-50 text-green-700 border-green-200' : ($score ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : ($startsSoon ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-gray-50 text-gray-600 border-gray-200')) }}">
                                            {{ $isOpen ? 'Open now' : ($score ? ($score . ' pts') : ($startsSoon ? 'Starts soon' : 'Closed')) }}
                                        </span>
                                        <p class="text-sm text-gray-500">Teacher: {{ $exam->teacher->name ?? 'TBD' }}</p>
                                    </div>
                                </div>

                                <div class="mt-5 grid gap-3 sm:grid-cols-3 text-sm text-gray-600">
                                    <div class="rounded-3xl bg-gray-50 p-4">
                                        <p class="font-semibold text-gray-900">{{ $exam->duration_minutes }} mins</p>
                                        <p class="mt-1">Duration</p>
                                    </div>
                                    <div class="rounded-3xl bg-gray-50 p-4">
                                        <p class="font-semibold text-gray-900">{{ $exam->starts_at->format('M d') }}</p>
                                        <p class="mt-1">Opens</p>
                                    </div>
                                    <div class="rounded-3xl bg-gray-50 p-4">
                                        <p class="font-semibold text-gray-900">{{ $exam->ends_at->format('M d') }}</p>
                                        <p class="mt-1">Due</p>
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="text-sm text-gray-500">{{ $exam->subject->code ?? 'SUBJ' }} • {{ $exam->section->name ?? 'Section' }}</p>
                                    <a href="{{ route('student.exams.show', $exam) }}" class="inline-flex items-center justify-center rounded-full bg-[#880000] px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#6a0000]">
                                        {{ $isOpen ? 'Take Exam' : ($startsSoon ? 'Preview' : 'Review') }}
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-[28px] border border-dashed border-gray-300 bg-white p-10 text-center">
                                <h3 class="text-lg font-semibold text-gray-900">No exams on your to-do list</h3>
                                <p class="mt-2 text-sm text-gray-500">Your published exams will appear here once instructors assign them.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-8 space-y-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Completed</h2>
                        <p class="mt-1 text-sm text-gray-500">Exams you’ve finished.</p>
                    </div>

                    <div class="rounded-[28px] border border-gray-200 bg-white p-6 shadow-sm max-h-[350px] overflow-y-auto custom-scrollbar">
                        <div class="space-y-4">
                            @forelse($recentSessions as $session)
                                <a href="{{ route('student.results.show', $session) }}" class="block no-underline">
                                    <article class="rounded-[28px] border border-gray-200 bg-gray-50 p-5 hover:shadow-sm transition">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="text-lg font-semibold text-gray-900">{{ $session->exam->title ?? 'Exam' }}</p>
                                                <p class="mt-1 text-sm text-gray-500">{{ $session->exam->subject->code ?? '' }} • {{ $session->exam->section->name ?? '' }}</p>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-3">
                                                <span class="rounded-full bg-[#880000] px-3 py-1 text-xs font-semibold text-white">{{ $session->score ?? 'N/A' }} pts</span>
                                                <span class="text-sm text-gray-500">{{ optional($session->submitted_at)->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </article>
                                </a>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-sm text-gray-500">No completed exams yet. Once you finish an exam, it will appear here.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

            <aside class="space-y-6 lg:max-h-[calc(100vh-280px)] lg:overflow-y-auto pr-1">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">Classes</h2>
                    <p class="mt-1 text-sm text-gray-500">Enrolled sections.</p>
                </div>
                
                <div class="rounded-[28px] border border-gray-200 bg-white p-6 shadow-sm max-h-[400px] overflow-y-auto">
                    <div class="space-y-3">
                        @forelse($classes as $section)
                            <div class="rounded-3xl border border-gray-100 bg-gray-50 p-4">
                                <p class="font-semibold text-gray-900">{{ $section->name }}</p>
                                <p class="mt-1 text-sm text-gray-600">{{ $section->subject->code ?? 'ND' }} • {{ $section->subject->name ?? 'No subject name' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">You're not enrolled in any sections yet.</p>
                        @endforelse
                    </div>
                </div>
            </aside>

        </div>
    </div>
</div>
@endsection