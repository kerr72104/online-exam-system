@extends('layouts.app')
@section('title', $exam->title)
@section('content')
<div class="bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8 pb-6 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-900">{{ $exam->title }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $exam->subject->name ?? 'Subject' }}</p>
        </div>

        <form action="{{ route('student.exams.submit', $exam) }}" method="POST" id="examForm">
            @csrf

            @forelse($exam->questions as $index => $question)
                <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <span class="text-[#880000]">Question {{ $index + 1 }}.</span> {{ $question->body }}
                        </h3>
                    </div>

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

            @if($exam->questions->count() > 0)
                <div class="sticky bottom-0 bg-white border-t border-gray-200 p-4 mt-8 flex justify-end items-center gap-4">
                    <div class="flex items-center gap-3">
                        <button id="submitBtn" type="submit" class="px-8 py-3 bg-[#880000] text-white font-bold rounded-lg hover:bg-[#660000] transition-colors">
                            Submit Exam
                        </button>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function(){
        const autosaveUrl = '{{ route('student.exams.autosave', $exam) }}';
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const form = document.getElementById('examForm');
        // preload saved answers
        const answersMap = {!! json_encode($answersMap ?? []) !!};
        for (const [qId, choiceId] of Object.entries(answersMap)) {
            const input = document.querySelector("input[name=\"answers["+qId+"]\"][value=\""+choiceId+"\"]");
            if (input) input.checked = true;
        }

        function collectAnswers(){
            const data = {};
            const inputs = form.querySelectorAll('input[type="radio"]');
            inputs.forEach(i => {
                if (i.checked){
                    const name = i.name; // answers[123]
                    const match = name.match(/answers\[(\d+)\]/);
                    if (match) data[match[1]] = parseInt(i.value);
                }
            });
            return data;
        }

        async function autosave(){
            try{
                const payload = { answers: collectAnswers() };
                await fetch(autosaveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload),
                    keepalive: true,
                });
            }catch(e){
                console.warn('Autosave failed', e);
            }
        }

        // autosave every 10s
        // autosave every 10s
        const autosaveInterval = setInterval(() => {
            autosave();
        }, 10000);

        // Also autosave immediately when student selects an option
        form.addEventListener('change', function(e){
            if (e.target && e.target.name && e.target.name.startsWith('answers[')){
                autosave();
            }
        });

        // ensure autosave on page unload
        window.addEventListener('beforeunload', function(){
            try{
                const payloadObj = { answers: collectAnswers(), _token: csrf };
                const blob = new Blob([JSON.stringify(payloadObj)], { type: 'application/json' });
                navigator.sendBeacon(autosaveUrl, blob);
            }catch(e){}
        });

        // stop autosave on manual submit
        form.addEventListener('submit', function(){
            clearInterval(autosaveInterval);
        });
    })();
</script>
@endpush
