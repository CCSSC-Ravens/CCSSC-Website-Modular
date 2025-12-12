<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-[#FDFDFC] min-h-screen">
    <nav class="bg-white border-b border-[#e3e3e0] px-6 py-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-[#1b1b18]">Admin Dashboard</h1>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="text-[#706f6c] hover:text-[#1b1b18] transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-[#e3e3e0] p-6">
            <h2 class="text-xl font-semibold text-[#1b1b18] mb-4">Welcome, {{ Auth::guard('admin')->user()->email }}</h2>
            <p class="text-[#706f6c]">You are logged in to the admin panel.</p>
        </div>
    </main>
</body>
</html>

