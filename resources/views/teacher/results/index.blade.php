@extends('layouts.app')

@section('content')

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-[#880000]">Results Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Overview of all published exams and student performance.</p>
        </div>

        @if($exams->isEmpty())
            <div class="bg-white rounded-xl border border-gray-100 p-12 text-center text-gray-400 text-sm">
                No published exams yet.
            </div>
        @else
            <div class="space-y-6">
                @foreach($exams as $exam)
                    <div class="bg-white rounded-xl border border-gray-100 p-6">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">{{ $exam->title }}</h2>
                                <p class="text-xs text-gray-500 mt-1 font-medium tracking-wide uppercase">
                                    {{ $exam->subject->code ?? '—' }} &bull; {{ $exam->section->name ?? '—' }} &bull;
                                    {{ $exam->question_count }} Questions &bull; {{ $exam->duration_minutes }} Mins
                                </p>
                            </div>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium border
                                @if($exam->status === 'published') bg-green-50 text-green-700 border-green-200
                                @else bg-gray-50 text-gray-600 border-gray-200
                                @endif">
                                {{ ucfirst($exam->status) }}
                            </span>
                        </div>

                        {{-- Stats Grid --}}
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Total Students</p>
                                <p class="text-lg font-bold text-gray-700">{{ $exam->total_students }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Submitted</p>
                                <p class="text-lg font-bold text-gray-700">{{ $exam->submitted_count }}</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-3 text-center">
                                <p class="text-[10px] text-blue-400 uppercase font-bold mb-1">Avg Score</p>
                                <p class="text-lg font-bold text-blue-700">
                                    {{ $exam->avg_score !== null ? number_format($exam->avg_score, 1) : '—' }}
                                </p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3 text-center">
                                <p class="text-[10px] text-green-400 uppercase font-bold mb-1">Highest</p>
                                <p class="text-lg font-bold text-green-700">{{ $exam->highest_score ?? '—' }}</p>
                            </div>
                            <div class="bg-red-50 rounded-lg p-3 text-center">
                                <p class="text-[10px] text-red-400 uppercase font-bold mb-1">Lowest</p>
                                <p class="text-lg font-bold text-red-700">{{ $exam->lowest_score ?? '—' }}</p>
                            </div>
                        </div>

                        <a href="{{ route('teacher.results.show', $exam) }}"
                           class="inline-flex items-center text-sm font-medium text-[#880000] hover:text-[#6a0000] hover:underline">
                           View detailed results &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection