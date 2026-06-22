@extends('layouts.app')
@section('title', 'PUP OES')
@section('content')
    <section class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center space-x-2 bg-red-50 text-[#880000] px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider">
                        <span>Official Examination Portal</span>
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight tracking-tight">
                        PUP Online <br class="hidden sm:inline" />
                        <span class="text-[#880000]">Examination System</span>
                    </h1>
                    
                    <p class="text-gray-600 text-lg sm:text-xl max-w-2xl leading-relaxed">
                        A lightweight, secure, and easy-to-use system for creating and administering exams. 
                        Built for educators and students to streamline assessment workflows.
                    </p>

                    <div class="pt-2 flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#FFD700] text-[#880000] font-bold 
                                                                    rounded-lg shadow-md hover:bg-yellow-400 transition-colors duration-200">
                            Create an account
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-200 rounded-lg text-gray-700 
                                                                font-medium hover:bg-gray-50 hover:border-gray-300 transition-colors duration-200">
                            Log in to portal
                        </a>
                    </div>

                    <ul class="pt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700 border-t border-gray-100">
                        <li class="flex items-center space-x-3">
                            <span class="flex-shrink-0 w-1 h-1 bg-red-700 rounded-full flex items-center justify-center text-xs font-bold"></span>
                            <span>Multiple choice</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span class="flex-shrink-0 w-1 h-1 bg-red-700 rounded-full flex items-center justify-center text-xs font-bold"></span>
                            <span>Timed sessions and auto-submit</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span class="flex-shrink-0 w-1 h-1 bg-red-700 rounded-full flex items-center justify-center text-xs font-bold"></span>
                            <span>Per-question scoring and feedback</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span class="flex-shrink-0 w-1 h-1 bg-red-700 rounded-full flex items-center justify-center text-xs font-bold"></span>
                            <span>Role-based teacher and student access</span>
                        </li>
                    </ul>
                </div>

                <div class="lg:col-span-5">
                    <img src="{{ asset('images/landing-illustration.png') }}" alt="Illustration of OES or PUP na lang" class="w-full rounded-lg shadow-md">
                </div>

            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Why choose PUP OES?</h2>
                <p class="text-gray-500 text-sm">Engineered custom-tailored workflows built for modern academic requirements.</p>
            </div>
            
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <h4 class="font-bold text-gray-800 text-base">Secure System</h4>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">Session-based access control and tight role-based permissions keep exam questions locked down.</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <h4 class="font-bold text-gray-800 text-base">Highly Flexible</h4>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">Supports diverse test formats, complex question banks, multi-tiered scoring, and auto-feedback options.</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <h4 class="font-bold text-gray-800 text-base">Fast Performance</h4>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">Optimized infrastructure for exceptionally low-latency delivery ensuring a smooth user experience.</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <h4 class="font-bold text-gray-800 text-base">Open & Extendable</h4>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">Easy to customize, scale, and integrate cleanly with third-party localized institutional frameworks.</p>
                </div>
            </div>
        </div>
    </section>
@endsection