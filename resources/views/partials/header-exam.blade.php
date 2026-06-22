<header class="bg-[#880000] text-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <div class="flex items-center space-x-4">
                <h1 class="font-bold text-lg hidden sm:block">
                    {{ optional($exam)->title ?? 'COMP 20133 - Software Engineering' }}
                </h1>
                <div class="border-l border-white/20 h-6"></div>
            </div>

            <div class="flex items-center space-x-5">
                <div class="flex items-center text-[#FFD700]">
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-mono font-bold text-lg tracking-wider" id="exam-timer">
                        --:--:--
                    </span>
                </div>

                <form action="{{ route('student.exams.submit', optional($exam)->id) }}" method="POST" id="exam-submit-form" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="bg-white text-[#880000] font-bold px-4 py-1.5 rounded-md hover:bg-gray-100 transition-colors text-sm">
                        Finish Exam
                    </button>
                </form>
            </div>

        </div>
    </div>
</header>

<script>
    // Grab duration from database, convert to seconds (fallback to 90 mins if missing)
    let timeRemaining = {{ optional($exam)->duration_minutes ?? 90 }} * 60; 
    
    const timerElement = document.getElementById('exam-timer');

    const countdown = setInterval(() => {
        if (timeRemaining <= 0) {
            clearInterval(countdown);
            timerElement.innerHTML = "00:00:00";
            
            // Add your auto-submit logic here later
            alert("Time is up! Your exam will now be submitted.");
            return;
        }

        // Calculate hours, minutes, and seconds
        let hours = Math.floor(timeRemaining / 3600);
        let minutes = Math.floor((timeRemaining % 3600) / 60);
        let seconds = timeRemaining % 60;

        // Format to always show two digits (e.g., 09 instead of 9)
        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        // Update the HTML
        timerElement.innerHTML = `${hours}:${minutes}:${seconds}`;

        // Decrease time by 1 second
        timeRemaining--;

    }, 1000); 
</script>