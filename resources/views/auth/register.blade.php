@extends('layouts.app')

@section('content')
<div class="fixed inset-0 flex items-center justify-center py-12 px-4">
    <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-md">
        
        <div class="text-center mb-6">
            <img src="{{ asset('PUPLogo.png') }}" alt="PUP Logo" class="w-16 h-16 mx-auto mb-3 object-cover rounded-full">
            <h1 class="text-2xl font-bold text-[#880000]">Create an Account</h1>
            <p class="text-gray-500 text-sm mt-1">Register as a student to get started</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4" novalidate>
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">
                @error('name')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">
                @error('email')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">
                @error('password')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>

            <button type="submit" class="w-full bg-[#880000] text-white py-2.5 rounded-lg font-bold hover:bg-[#660000] mt-4 cursor-pointer">
                Create Account
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-gray-100 text-center text-sm">
            <p class="text-gray-500">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-[#880000] font-bold hover:underline">Login</a>
            </p>
            <a href="{{ route('landing') }}" class="block mt-3 text-gray-400 hover:text-gray-600">
                &larr; Back to Home
            </a>
        </div>

    </div>
</div>
@endsection