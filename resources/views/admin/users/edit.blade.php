@extends('layouts.app')

@section('content')

    <div class="max-w-lg mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-2xl font-semibold text-[#880000] mb-6">Edit User</h1>

        <div class="bg-white rounded-xl border border-gray-100 p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm">
                        <option value="admin"   {{ old('role', $user->role) === 'admin'   ? 'selected' : '' }}>Admin</option>
                        <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        New Password <span class="text-gray-400 font-normal">(leave blank to keep current)</span>
                    </label>
                    <input type="password" name="password"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm">
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit"
                        class="inline-flex items-center justify-center bg-[#880000] text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-[#6a0000]">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="text-sm font-medium text-gray-500 hover:text-gray-800 hover:underline">
                       Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

@endsection