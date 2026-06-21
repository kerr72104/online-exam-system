@extends('layouts.app')

@section('content')

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('teacher.results.index') }}"
               class="text-sm font-medium text-gray-500 hover:text-[#880000] hover:underline">
               &larr; Back to Results
            </a>
            <h1 class="text-2xl font-semibold text-[#880000] mt-2">{{ $exam->title }}</h1>
            <p class="text-xs text-gray-400 mt-1 font-medium tracking-wide uppercase">
                {{ $exam->subject->code ?? '—' }} &bull;
                {{ $exam->section->name ?? '—' }} &bull;
                {{ $questionCount }} Questions &bull;
                {{ $exam->duration_minutes }} Mins
            </p>
        </div>

        {{-- Summary Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Total Students</p>
                <p class="text-xl font-bold text-gray-700">{{ $stats['total_students'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Submitted</p>
                <p class="text-xl font-bold text-gray-700">{{ $stats['submitted_count'] }}</p>
            </div>
            <div class="bg-blue-50 rounded-xl border border-blue-100 p-4 text-center">
                <p class="text-[10px] text-blue-400 uppercase font-bold mb-1">Average</p>
                <p class="text-xl font-bold text-blue-700">
                    {{ $stats['avg_score'] !== null ? $stats['avg_score'] : '—' }}
                </p>
            </div>
            <div class="bg-green-50 rounded-xl border border-green-100 p-4 text-center">
                <p class="text-[10px] text-green-400 uppercase font-bold mb-1">Highest</p>
                <p class="text-xl font-bold text-green-700">{{ $stats['highest_score'] ?? '—' }}</p>
            </div>
            <div class="bg-red-50 rounded-xl border border-red-100 p-4 text-center">
                <p class="text-[10px] text-red-400 uppercase font-bold mb-1">Lowest</p>
                <p class="text-xl font-bold text-red-700">{{ $stats['lowest_score'] ?? '—' }}</p>
            </div>
        </div>

        {{-- Student Results Table --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-800">Student Scores</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold">
                        <tr>
                            <th class="px-6 py-3">Student</th>
                            <th class="px-6 py-3">Score</th>
                            <th class="px-6 py-3">Percentage</th>
                            <th class="px-6 py-3">Submitted At</th>
                            <th class="px-6 py-3">Result</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($sessions as $session)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $session->student->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $session->student->email }}</p>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $session->score }} / {{ $questionCount }}</td>
                                <td class="px-6 py-4">
                                    @php $pct = $questionCount > 0 ? round(($session->score / $questionCount) * 100) : 0; @endphp
                                    <div class="flex items-center gap-3">
                                        <div class="w-20 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-1.5 rounded-full {{ $pct >= 75 ? 'bg-green-500' : 'bg-red-400' }}"
                                                 style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-600 font-medium">{{ $pct }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    {{ $session->submitted_at?->format('M d, Y h:i A') ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        {{ $pct >= 75 ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                        {{ $pct >= 75 ? 'Passed' : 'Failed' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">No submissions yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Per-Question Breakdown --}}
        <div class="mb-4">
            <h2 class="text-sm font-semibold text-gray-800">Question Breakdown</h2>
            <p class="text-xs text-gray-400 mt-0.5">How many students answered each question correctly.</p>
        </div>

        <div class="space-y-3">
            @foreach($exam->questions as $i => $question)
                @php
                    $correctCount = $sessions->sum(function($session) use ($question) {
                        return $session->answers->where('question_id', $question->id)->first()?->isCorrect() ? 1 : 0;
                    });
                    $total = $sessions->count();
                    $pct = $total > 0 ? round(($correctCount / $total) * 100) : 0;
                @endphp

                <div class="bg-white rounded-xl border border-gray-100 p-4">
                    <div class="flex items-start justify-between gap-4">
                        <p class="text-sm text-gray-700 leading-relaxed">
                            <span class="font-bold text-[#880000] mr-2">{{ $i + 1 }}.</span>
                            {{ Str::limit($question->body, 120) }}
                        </p>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-bold {{ $pct >= 75 ? 'text-green-600' : 'text-red-500' }}">
                                {{ $correctCount }}/{{ $total }}
                            </p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $pct }}%</p>
                        </div>
                    </div>

                    <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                        <div class="h-1.5 rounded-full {{ $pct >= 75 ? 'bg-green-500' : 'bg-red-400' }}"
                             style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection