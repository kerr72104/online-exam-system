@extends('layouts.app')
@section('title', 'PUP OES')
@section('content')
    <section class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center space-x-2 bg-red-50 text-[#880000] px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider">
                        <span>Official Examination Portal</span>
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight tracking-tight">
                        PUP Online <br class="hidden sm:inline" />
                        <span class="text-[#880000]">Examination System</span>
                    </h1>
                    
                    <p class="text-gray-600 text-lg sm:text-xl max-w-2xl leading-relaxed">
                        The official PUP examination portal for Polytechnic University of the Philippines students and faculty.
                        Securely manage campus exams, monitor section performance, and deliver standardized assessments across PUP sections.
                    </p>

                    <div class="pt-2 flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#FFD700] text-[#880000] font-bold 
                                                                    rounded-lg shadow-md hover:bg-yellow-400 transition-colors duration-200">
                            Join PUP OES
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-200 rounded-lg text-gray-700 
                                                                font-medium hover:bg-gray-50 hover:border-gray-300 transition-colors duration-200">
                            Log in to PUP portal
                        </a>
                    </div>

                    <ul class="pt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700 border-t border-gray-100">
                        <li class="flex items-center space-x-3">
                            <span class="flex-shrink-0 w-1 h-1 bg-red-700 rounded-full flex items-center justify-center text-xs font-bold"></span>
                            <span>PUP-ready exam delivery</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span class="flex-shrink-0 w-1 h-1 bg-red-700 rounded-full flex items-center justify-center text-xs font-bold"></span>
                            <span>Timed sessions with auto-submit for exams</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span class="flex-shrink-0 w-1 h-1 bg-red-700 rounded-full flex items-center justify-center text-xs font-bold"></span>
                            <span>Grade tracking and reporting for each section</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span class="flex-shrink-0 w-1 h-1 bg-red-700 rounded-full flex items-center justify-center text-xs font-bold"></span>
                            <span>Built for PUP instructors and learners</span>
                        </li>
                    </ul>
                </div>

                <div class="lg:col-span-5">
                    <div class="h-80 lg:h-[420px] bg-[#880000] rounded-3xl shadow-xl p-8 flex flex-col justify-between text-white">
                        <div>
                            <span class="text-sm uppercase tracking-[0.3em] text-white/80">PUP OES Inspiration</span>
                            <h3 class="mt-4 text-3xl font-extrabold leading-tight">Your next achievement starts with one question.</h3>
                        </div>
                        <div class="mt-6">
                            <p class="text-sm text-white/90 leading-relaxed">"Study hard, stay curious, and remember that every exam is a chance to show how much you’ve grown. Your hard work today becomes the success you celebrate tomorrow."</p>
                            <p class="mt-6 text-xs uppercase tracking-[0.28em] text-white/70">— PUP OES</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Why choose PUP OES?</h2>
                <p class="text-gray-500 text-sm">Designed for PUP’s academic community, from lecturers setting exams to students managing assessment schedules.</p>
            </div>
            
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <h4 class="font-bold text-gray-800 text-base">Secure System</h4>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">Secure student access and exam confidentiality built for campus assessment standards.</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <h4 class="font-bold text-gray-800 text-base">Section-driven Workflow</h4>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">Manage exams by section, subject, and instructor to support PUP class structures.</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <h4 class="font-bold text-gray-800 text-base">Reliable Speed</h4>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">Optimized for PUP cohort sizes to keep exam delivery smooth under load.</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <h4 class="font-bold text-gray-800 text-base">Built for PUP Growth</h4>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">Customizable and scalable to support expanding exam needs across PUP campuses.</p>
                </div>
            </div>
        </div>
    </section>
@endsection