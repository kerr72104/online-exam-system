@extends('layouts.app')

@section('content')

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <h1 class="text-2xl font-semibold text-[#880000]">Exams</h1>
            <a href="{{ route('teacher.exams.create') }}"
                class="inline-flex items-center gap-1.5 bg-[#880000] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-[#6a0000]">
                + Create Exam
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->has('publish'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6 text-sm font-medium">
                {{ $errors->first('publish') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left whitespace-nowrap min-w-[650px]">
                    <thead class="bg-[#880000] border-b-2 border-[#880000] uppercase text-xs text-white font-medium tracking-wide">
                        <tr>
                            <th class="px-5 py-3">Title</th>
                            <th class="px-5 py-3">Subject</th>
                            <th class="px-5 py-3">Section</th>
                            <th class="px-5 py-3">Duration</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($exams as $exam)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3.5 font-medium text-gray-800">{{ $exam->title }}</td>
                                <td class="px-5 py-3.5 text-xs">
                                    <span class="font-mono bg-gray-100 text-gray-500 px-2 py-0.5 rounded">
                                        {{ $exam->subject->code ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $exam->section->name ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $exam->duration_minutes }} mins</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium
                                        @if($exam->status === 'published') bg-green-50 text-green-700 border border-green-200
                                        @elseif($exam->status === 'closed') bg-gray-50 text-gray-600 border border-gray-200
                                        @else bg-yellow-50 text-yellow-700 border border-yellow-200
                                        @endif">
                                        {{ ucfirst($exam->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('teacher.exams.show', $exam) }}"
                                           class="text-gray-500 text-xs font-medium hover:text-[#880000] hover:underline">View</a>

                                        @if($exam->status === 'draft')
                                            <a href="{{ route('teacher.exams.edit', $exam) }}"
                                                class="text-blue-600 text-xs font-medium hover:underline">Edit</a>

                                            <form method="POST" action="{{ route('teacher.exams.publish', $exam) }}"
                                                onsubmit="return confirm('Publish this exam? It cannot be edited after publishing.')">
                                                @csrf
                                                <button type="submit" class="text-green-600 text-xs font-medium hover:underline">Publish</button>
                                            </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('teacher.exams.destroy', $exam) }}"
                                            onsubmit="return confirm('Delete this exam?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 text-xs font-medium hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-gray-400 text-sm">
                                    No exams yet. Create your first exam.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($exams->hasPages())
                <div class="px-5 py-4 border-t bg-gray-50">
                    {{ $exams->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection