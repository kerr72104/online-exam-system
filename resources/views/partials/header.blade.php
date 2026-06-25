<header class="bg-[#880000] text-white shadow-md sticky top-0 z-50" x-data="{ menuOpen: false, profileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <div class="flex items-center">
                <img src="{{ asset('PUPLogo.png') }}" alt="PUP Logo" class="w-8 h-8 object-cover rounded-full mr-3">
                <div class="flex flex-col justify-center">
                    <span class="font-bold text-lg leading-none">
                        @if(auth()->user()->role === 'admin')
                            Admin Panel
                        @elseif(auth()->user()->role === 'teacher')
                            Teacher Dashboard
                        @else
                            Student Dashboard
                        @endif
                    </span>
                    <span class="text-xs text-gray-300 mt-1 font-medium tracking-wide">PUP OES</span>
                </div>
            </div>

            <nav class="hidden md:flex space-x-6 items-center">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ request()->routeIs('admin.users.*') ? 'text-[#FFD700] font-semibold border-b-2 border-[#FFD700]' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} pb-1">
                        Users
                    </a>
                    <a href="{{ route('admin.subjects.index') }}"
                       class="{{ request()->routeIs('admin.subjects.*') ? 'text-[#FFD700] font-semibold border-b-2 border-[#FFD700]' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} pb-1">
                        Subjects
                    </a>
                    <a href="{{ route('admin.sections.index') }}"
                       class="{{ request()->routeIs('admin.sections.*') ? 'text-[#FFD700] font-semibold border-b-2 border-[#FFD700]' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} pb-1">
                        Sections
                    </a>
                @endif

                @if(auth()->user()->role === 'teacher')
                    <a href="{{ route('teacher.questions.index') }}"
                       class="{{ request()->routeIs('teacher.questions.*') ? 'text-[#FFD700] font-semibold border-b-2 border-[#FFD700]' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} pb-1">
                        Questions
                    </a>
                    <a href="{{ route('teacher.exams.index') }}"
                       class="{{ request()->routeIs('teacher.exams.*') ? 'text-[#FFD700] font-semibold border-b-2 border-[#FFD700]' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} pb-1">
                        Exams
                    </a>
                    <a href="{{ route('teacher.results.index') }}"
                       class="{{ request()->routeIs('teacher.results.*') ? 'text-[#FFD700] font-semibold border-b-2 border-[#FFD700]' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} pb-1">
                        Results
                    </a>
                @endif
            </nav>

            <div class="flex items-center gap-3">

                @if(auth()->user()->role !== 'student')
                <button class="md:hidden text-white focus:outline-none"
                        @click="menuOpen = !menuOpen"
                        aria-label="Toggle menu">
                    <svg x-show="!menuOpen" ...></svg>
                    <svg x-show="menuOpen" ...></svg>
                </button>
                @endif

                <div class="flex items-center border-l border-white/20 pl-3"
                     @click.outside="profileOpen = false">
                    <div class="flex items-center space-x-2 cursor-pointer select-none"
                         @click="profileOpen = !profileOpen">
                        <div class="w-8 h-8 rounded-full bg-white text-[#880000] flex items-center justify-center font-bold text-sm">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <span class="hidden md:block font-medium text-sm hover:text-[#FFD700]">
                            {{ auth()->user()->name ?? 'User' }}
                        </span>
                    </div>

                    <div x-show="profileOpen"
                         x-transition
                         class="absolute right-4 top-14 w-36 bg-white rounded-md shadow-lg overflow-hidden z-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 font-medium">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div x-show="menuOpen"
         x-transition
         @click.outside="menuOpen = false"
         class="md:hidden bg-[#6e0000] px-4 pb-4 pt-2 flex flex-col gap-1">

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.users.index') }}"
               class="{{ request()->routeIs('admin.users.*') ? 'text-[#FFD700] font-semibold' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} block py-2 border-b border-white/10">
                Users
            </a>
            <a href="{{ route('admin.subjects.index') }}"
               class="{{ request()->routeIs('admin.subjects.*') ? 'text-[#FFD700] font-semibold' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} block py-2 border-b border-white/10">
                Subjects
            </a>
            <a href="{{ route('admin.sections.index') }}"
               class="{{ request()->routeIs('admin.sections.*') ? 'text-[#FFD700] font-semibold' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} block py-2">
                Sections
            </a>
        @endif

        @if(auth()->user()->role === 'teacher')
            <a href="{{ route('teacher.questions.index') }}"
               class="{{ request()->routeIs('teacher.questions.*') ? 'text-[#FFD700] font-semibold' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} block py-2 border-b border-white/10">
                Questions
            </a>
            <a href="{{ route('teacher.exams.index') }}"
               class="{{ request()->routeIs('teacher.exams.*') ? 'text-[#FFD700] font-semibold' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} block py-2 border-b border-white/10">
                Exams
            </a>
            <a href="{{ route('teacher.results.index') }}"
               class="{{ request()->routeIs('teacher.results.*') ? 'text-[#FFD700] font-semibold' : 'text-gray-200 hover:text-[#FFD700] font-medium' }} block py-2">
                Results
            </a>
        @endif

    </div>
</header>