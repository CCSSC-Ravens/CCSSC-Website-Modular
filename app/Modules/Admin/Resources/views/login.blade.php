<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0c0c0c;
        }
        .input-group:focus-within svg {
            color: #ea580c;
        }
        .input-group:focus-within {
            border-color: #ea580c;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Animated Grid Background -->
    <canvas id="grid-canvas" class="fixed inset-0 w-full h-full z-0 pointer-events-none"></canvas>

    <div class="w-full max-w-[420px] bg-[#141414] rounded-2xl shadow-2xl border border-white/5 p-8 relative overflow-hidden backdrop-blur-sm z-10">
        {{-- Top Glow Effect --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-1 bg-orange-600 blur-[20px] opacity-70"></div>

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">Sign In</h1>
            <p class="text-neutral-500 text-sm">Please enter your credentials to continue.</p>
        </div>

        <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
            @csrf

            {{-- Email Field --}}
            <div class="space-y-2">
                <label for="email" class="text-[11px] font-bold tracking-wider text-neutral-500 uppercase">Email</label>
                <div class="relative group input-group border border-white/5 rounded-lg bg-[#0a0a0a] transition-colors duration-200">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-neutral-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        class="block w-full pl-10 pr-3 py-2.5 bg-transparent text-gray-200 placeholder-neutral-700 focus:outline-none sm:text-sm font-medium" 
                        placeholder="name@company.com"
                        required 
                        autofocus
                        autocomplete="email"
                    >
                </div>
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Field --}}
            <div class="space-y-2">
                <label for="password" class="text-[11px] font-bold tracking-wider text-neutral-500 uppercase">Password</label>
                <div class="relative group input-group border border-white/5 rounded-lg bg-[#0a0a0a] transition-colors duration-200">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-neutral-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="block w-full pl-10 pr-10 py-2.5 bg-transparent text-gray-200 placeholder-neutral-700 focus:outline-none sm:text-sm font-medium tracking-widest" 
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-neutral-600 hover:text-neutral-400 focus:outline-none transition-colors">
                        <svg id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eye-off-icon" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember & Forgot --}}
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center space-x-2.5 cursor-pointer group">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-neutral-700 bg-[#0a0a0a] text-orange-600 focus:ring-orange-600/30 focus:ring-offset-0 transition-colors">
                    <span class="text-sm text-neutral-500 group-hover:text-neutral-400 transition-colors">Remember me</span>
                </label>
                <a href="#" class="text-sm font-medium text-orange-700 hover:text-orange-600 transition-colors">Forgot password?</a>
            </div>

            <button 
                type="submit" 
                class="w-full bg-[#B13407] hover:bg-[#c2410c] text-white font-semibold py-3 px-4 rounded-lg shadow-lg shadow-orange-900/10 transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-orange-500/50 mt-6 tracking-wide uppercase text-sm"
            >
                Sign In
            </button>
        </form>

        {{-- Footer --}}
        <div class="mt-8 pt-6 border-t border-white/5">
            <div class="flex items-start gap-3">
                <svg class="h-5 w-5 shrink-0 text-orange-900/40 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-[11px] text-neutral-600 leading-relaxed font-medium">
                    Restricted access. Unauthorized use is prohibited.
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }

        // Animated Grid Background
        (function() {
            const canvas = document.getElementById('grid-canvas');
            const ctx = canvas.getContext('2d');
            
            let width, height;
            const gridSize = 50;
            const runners = [];
            
            function resize() {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            }
            window.addEventListener('resize', resize);
            resize();
            
            class Runner {
                constructor() {
                    this.reset();
                    // Start at random progress in life cycle to avoid synchronized spawn
                    this.life = Math.random() * this.maxLife;
                }
                
                reset() {
                    this.isHorizontal = Math.random() > 0.5;
                    // Snap to grid
                    if (this.isHorizontal) {
                        this.y = Math.round((Math.random() * height) / gridSize) * gridSize;
                        this.x = Math.random() * width;
                        this.dx = (Math.random() * 0.5 + 0.2) * (Math.random() > 0.5 ? 1 : -1); 
                        this.dy = 0;
                    } else {
                        this.x = Math.round((Math.random() * width) / gridSize) * gridSize;
                        this.y = Math.random() * height;
                        this.dy = (Math.random() * 0.5 + 0.2) * (Math.random() > 0.5 ? 1 : -1);
                        this.dx = 0;
                    }
                    
                    this.life = 100 + Math.random() * 100;
                    this.maxLife = this.life;
                    this.length = 50 + Math.random() * 200; // Varying lengths
                }
                
                update() {
                    this.x += this.dx;
                    this.y += this.dy;
                    
                    this.life--;
                    
                    // Reset if out of bounds or dead
                    if (this.life <= 0 || 
                        (this.dx > 0 && this.x - this.length > width) || 
                        (this.dx < 0 && this.x + this.length < 0) ||
                        (this.dy > 0 && this.y - this.length > height) ||
                        (this.dy < 0 && this.y + this.length < 0)) {
                        
                        // Small chance to stay dead for a bit (throttling), but here we just reset immediately to keep activity up
                        this.reset();
                    }
                }
                
                draw() {
                    // Fade in and out
                    const opacity = Math.min(1, this.life / 20) * Math.min(1, (this.maxLife - this.life) / 20) * 0.6;
                    
                    if (opacity <= 0.01) return;
                    
                    const headX = this.x;
                    const headY = this.y;
                    let tailX, tailY;
                    
                    if (this.isHorizontal) {
                        tailX = headX - (Math.sign(this.dx) * this.length);
                        tailY = headY;
                    } else {
                        tailX = headX;
                        tailY = headY - (Math.sign(this.dy) * this.length);
                    }
                    
                    const gradient = ctx.createLinearGradient(headX, headY, tailX, tailY);
                    gradient.addColorStop(0, `rgba(234, 88, 12, ${opacity})`); // Orange head
                    gradient.addColorStop(1, `rgba(234, 88, 12, 0)`);       // Transparent tail
                    
                    ctx.strokeStyle = gradient;
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(tailX, tailY);
                    ctx.lineTo(headX, headY);
                    ctx.stroke();
                }
            }
            
            // spawn runners
            for(let i=0; i<30; i++) {
                runners.push(new Runner());
            }
            
            function animate() {
                ctx.clearRect(0, 0, width, height);
                
                // Draw static grid
                ctx.strokeStyle = 'rgba(255, 255, 255, 0.03)';
                ctx.lineWidth = 1;
                ctx.beginPath();
                
                // Draw Vertical Lines
                for(let x = 0; x <= width; x += gridSize) {
                    ctx.moveTo(x + 0.5, 0);
                    ctx.lineTo(x + 0.5, height);
                }
                
                // Draw Horizontal Lines
                for(let y = 0; y <= height; y += gridSize) {
                    ctx.moveTo(0, y + 0.5);
                    ctx.lineTo(width, y + 0.5);
                }
                ctx.stroke();
                
                // Draw Runners
                runners.forEach(r => {
                    r.update();
                    r.draw();
                });
                
                requestAnimationFrame(animate);
            }
            
            animate();
        })();
    </script>
</body>
</html>

