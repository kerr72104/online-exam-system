<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased flex flex-col min-h-screen">

    @if(request()->routeIs('student.exams.*')) 
        @include('partials.header-exam', ['exam' => request()->route('exam') ?? null])
    @elseif(auth()->check()) 
        @include('partials.header')
    @else 
        @include('partials.header-landing')
    @endif

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>