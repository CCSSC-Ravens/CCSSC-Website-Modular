<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#B13407] px-10 pt-10  h-screen">
    <section class="flex flex-col h-full w-full">
        <nav class="flex justify-between text-white">
            <!-- Logo Section -->
            <div>
                <h2 class="font-bold text-3xl">Phoenixes</h2>
            </div>
            <!-- Navigation Links -->
            <div class="border border-white rounded-2xl w-1/3 flex items-center">
                <ul class="flex justify-around w-full">
                    <li>About Us</li>
                    <li>Committees</li>
                    <li>News</li>
                </ul>
            </div>
            <!-- Search Field -->
            <div>
                <input class="h-full bg-white text-orange-800 font-medium rounded-2xl p-2" type="text"
                    placeholder="Search Phoenixes">
            </div>
        </nav>
        <section class="flex-1 flex flex-col">
            <div class="mt-25">
                <h1 class="text-7xl font-bold mb-5">Soaring<br>beyond limits.</h1>
                <p class="text-xl mb-5">The official website of Gordon College - College of Computer Studies Student
                    Council.</p>
                <button class="rounded-2xl px-13 py-1 bg-orange-800 text-white font-medium">Visit Us</button>
            </div>
            <div class="mt-auto">
                <div>
                    <h2>Sa Pagitan microfilm</h2>
                    <label>Read moredsadsadasd</label>
                </div>
            </div>
        </section>
    </section>
</body>

</html>
