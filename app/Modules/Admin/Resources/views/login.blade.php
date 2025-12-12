<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-[#B13407] min-h-screen flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-md shadow-xl">
        <h1 class="text-3xl font-bold text-[#1b1b18] mb-6 text-center">Admin Login</h1>
        
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-[#1b1b18] mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required 
                    autofocus
                    class="w-full px-4 py-2 border border-[#e3e3e0] rounded-lg focus:outline-none focus:border-[#B13407] @error('email') border-red-500 @enderror"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-[#1b1b18] mb-2">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    class="w-full px-4 py-2 border border-[#e3e3e0] rounded-lg focus:outline-none focus:border-[#B13407] @error('password') border-red-500 @enderror"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-[#e3e3e0]">
                    <span class="ml-2 text-sm text-[#706f6c]">Remember me</span>
                </label>
            </div>

            <button 
                type="submit" 
                class="w-full bg-[#B13407] text-white font-medium py-2 px-4 rounded-lg hover:bg-[#8d2905] transition-colors"
            >
                Login
            </button>
        </form>
    </div>
</body>
</html>

