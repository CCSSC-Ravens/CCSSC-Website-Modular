<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>News | {{ config('app.name', 'Phoenixes') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-[#FDF5F0] font-sans antialiased">
    @if($featured)
        {{-- Hero Section - Full Screen with Latest News --}}
        <section 
            class="relative h-screen w-full bg-cover bg-center bg-no-repeat"
            @if($featured->thumbnail)
                style="background-image: url('{{ $featured->thumbnail }}');"
            @else
                style="background-image: url('{{ asset('images/news/default-hero.jpg') }}');"
            @endif
        >
            {{-- Gradient Overlay - Darker for better text visibility --}}
            <div class="absolute inset-0 bg-gradient-to-b from-[#B13407] via-[#8B0000]/70 to-[#5A0000]/90"></div>
            
            {{-- Content Container --}}
            <div class="relative z-10 h-full flex flex-col px-10 pt-10">
                {{-- Navbar --}}
                <x-navbar class="shrink-0" />

                {{-- Hero Content --}}
                <div class="flex-1 flex flex-col justify-center pb-24">
                    <div class="max-w-3xl">
                        {{-- Article Title --}}
                        <h1 
                            class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6 italic drop-shadow-lg" 
                            style="font-family: 'Instrument Sans', serif; text-shadow: 2px 2px 8px rgba(0,0,0,0.5);"
                        >
                            {{ $featured->title }}
                        </h1>
                        
                        {{-- Article Excerpt --}}
                        <p class="text-white/95 text-base md:text-lg leading-relaxed mb-8 max-w-2xl drop-shadow-md" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.4);">
                            {{ Str::limit(strip_tags($featured->content), 250) }}
                        </p>
                        
                        {{-- Read More Button --}}
                        <a 
                            href="{{ route('news.show', $featured) }}" 
                            class="inline-flex items-center gap-2 bg-[#8B0000] hover:bg-[#6B0000] text-white font-semibold px-8 py-3 rounded-full transition-all duration-300 hover:scale-105 shadow-lg"
                        >
                            Read more
                        </a>
                    </div>
                </div>

                {{-- Scroll Down Indicator --}}
                <div class="absolute bottom-8 left-0 flex items-center gap-2 text-white/90 drop-shadow-md">
                    <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                    <span class="text-sm font-medium" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">Scroll Down</span>
                </div>
            </div>
        </section>
    @else
        {{-- Fallback Header when no featured article --}}
        <header class="bg-[#B13407] px-10 pt-10 pb-6">
            <x-navbar />
        </header>
    @endif

    {{-- Main Content --}}
    <main class="px-6 md:px-10 py-16 bg-[#FDF5F0]">
        <div class="max-w-7xl mx-auto">
            {{-- Section Header --}}
            <div class="mb-12">
                <p class="text-sm text-gray-600 font-medium tracking-wide mb-2">Our Articles</p>
                <h2 class="text-4xl md:text-5xl font-bold italic text-[#8B0000]" style="font-family: 'Instrument Sans', serif;">
                    Trending now
                </h2>
            </div>

            @if($featured || $articles->count() > 0)
                {{-- Articles Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @if($featured)
                        {{-- Featured Article Card --}}
                        <a href="{{ route('news.show', $featured) }}" 
                           class="block bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group lg:col-span-2 lg:row-span-2">
                            {{-- Thumbnail --}}
                            <div class="aspect-video lg:aspect-[16/10] bg-gradient-to-br from-[#8B0000] to-[#B13407] relative overflow-hidden">
                                @if($featured->thumbnail)
                                    <img src="{{ $featured->thumbnail }}" alt="{{ $featured->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                    </div>
                                @endif
                                {{-- Featured Badge --}}
                                <div class="absolute top-4 left-4 bg-[#E85A24] text-white text-xs font-bold px-3 py-1 rounded-full">
                                    LATEST
                                </div>
                            </div>
                            
                            {{-- Content --}}
                            <div class="p-6 lg:p-8">
                                <p class="text-sm text-gray-500 mb-3">{{ $featured->created_at->format('F d, Y') }}</p>
                                <h3 class="text-2xl lg:text-3xl font-bold text-[#8B0000] group-hover:text-[#B13407] transition-colors mb-4 break-words">
                                    {{ $featured->title }}
                                </h3>
                                <p class="text-gray-600 line-clamp-3 break-words">
                                    {{ Str::limit(strip_tags($featured->content), 200) }}
                                </p>
                            </div>
                        </a>
                    @endif

                    {{-- Other Articles --}}
                    @foreach($articles as $article)
                        <a href="{{ route('news.show', $article) }}" 
                           class="block bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                            {{-- Thumbnail --}}
                            <div class="aspect-video bg-gradient-to-br from-[#8B0000] to-[#B13407] relative overflow-hidden">
                                @if($article->thumbnail)
                                    <img src="{{ $article->thumbnail }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Content --}}
                            <div class="p-5">
                                <p class="text-xs text-gray-500 mb-2">{{ $article->created_at->format('F d, Y') }}</p>
                                <h3 class="font-bold text-[#8B0000] group-hover:text-[#B13407] transition-colors line-clamp-2 mb-2 break-words">
                                    {{ $article->title }}
                                </h3>
                                <p class="text-sm text-gray-600 line-clamp-2 break-words">
                                    {{ Str::limit(strip_tags($article->content), 100) }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-700 mb-2">No Articles Yet</h2>
                    <p class="text-gray-500">Check back soon for the latest news and updates!</p>
                </div>
            @endif
        </div>
    </main>

    {{-- Footer --}}
    <x-footer />
    <x-feedback-button />
</body>

</html>
