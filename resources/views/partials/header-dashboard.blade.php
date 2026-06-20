<header class="bg-[#880000] text-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <div class="flex items-center">
                <img src="{{ asset('PUPLogo.png') }}" alt="PUP Logo" class="w-8 h-8 object-cover rounded-full mr-3">
                <a href="{{ route('student.dashboard') }}" class="flex flex-col justify-center hover:text-[#FFD700]">
                    <span class="font-bold text-lg leading-none">Student Dashboard</span>
                    <span class="text-xs text-gray-300 mt-1 font-medium tracking-wide">PUP OES</span>
                </a>
            </div>

            <nav class="hidden md:flex space-x-6 items-center">
                <a href="{{ route('student.dashboard') }}" 
                   class="{{ request()->routeIs('student.dashboard') ? 'text-[#FFD700] font-semibold border-b-2 border-[#FFD700]' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} pb-1">
                    My Exams
                </a>
                
                <a href="{{ route('student.results') }}" 
                   class="{{ request()->routeIs('student.results') ? 'text-[#FFD700] font-semibold border-b-2 border-[#FFD700]' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} pb-1">
                    Results
                </a>
            </nav>

        <div class="flex items-center border-l border-white/20 pl-5 relative group">
            <div class="flex items-center space-x-2 cursor-pointer pb-2">
                <div class="w-8 h-8 rounded-full bg-white text-[#880000] flex items-center justify-center font-bold text-sm">
                    {{ substr(auth()->user()->name ?? 'I', 0, 1) }}
                </div>
                <span class="hidden md:block font-medium text-sm hover:text-[#FFD700]">
                    {{ auth()->user()->name ?? 'Iskolar' }}
                </span>
            </div>

            <div class="absolute right-0 top-full w-32 bg-white rounded-md shadow-lg hidden group-hover:block overflow-hidden">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 font-medium">
                        Log Out
                    </button>
                </form>
            </div>
        </div>

        </div>
    </div>
</header>