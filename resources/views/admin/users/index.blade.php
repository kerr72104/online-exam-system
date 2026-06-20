@extends('layouts.app')

@section('content')

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <h1 class="text-2xl font-semibold text-[#880000]">Users</h1>
            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center gap-1.5 bg-[#880000] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-[#6a0000]">
                + Add User
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @php $currentRole = request('role', 'all'); @endphp
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach(['all' => 'All', 'admin' => 'Admin', 'teacher' => 'Teacher', 'student' => 'Student'] as $value => $label)
                <a href="{{ route('admin.users.index', $value !== 'all' ? ['role' => $value] : []) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-medium border
                       {{ $currentRole === $value
                           ? ($value === 'teacher' ? 'bg-[#FFD700] text-[#880000] border-[#d4b800]' : 'bg-[#880000] text-white border-[#880000]')
                           : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <p class="text-xs text-gray-400 mb-3">{{ $users->total() }} {{ Str::plural('user', $users->total()) }}</p>

        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left whitespace-nowrap min-w-[580px]">
                    <thead class="bg-[#880000] border-b-2 border-[#880000] text-[#880000] uppercase text-xs text-white font-medium tracking-wide">
                        <tr>
                            <th class="px-5 py-3">Name</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Role</th>
                            <th class="px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3.5 font-medium text-gray-800">{{ $user->name }}</td>
                                <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $user->email }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($user->role === 'admin') bg-[#880000] text-white
                                        @elseif($user->role === 'teacher') bg-[#FFD700] text-[#880000]
                                        @else bg-gray-100 text-gray-500
                                        @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="text-[#880000] text-xs font-medium hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 text-xs font-medium hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-gray-400 text-sm">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="px-5 py-4 border-t bg-gray-50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>

@endsection