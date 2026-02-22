<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Voice Clone App') }} - AI Voice Cloning</title>
    
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')

    <style>
        :root {
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.4);
            --bg-dark: #0f172a;
            --glass-bg: rgba(30, 41, 59, 0.7);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 10% 10%, rgba(99, 102, 241, 0.15) 0%, transparent 45%),
                radial-gradient(circle at 90% 90%, rgba(139, 92, 246, 0.15) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(30, 41, 59, 1) 0%, var(--bg-dark) 100%);
        }

        /* Ambient Background Particles */
        #ambient-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.4;
        }

        h1, h2, h3, .brand-logo {
            font-family: 'Outfit', sans-serif;
        }

        .layout-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: 280px;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .brand-logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #fff;
            text-decoration: none;
        }

        .brand-logo i {
            color: var(--primary);
            filter: drop-shadow(0 0 8px var(--primary-glow));
        }

        .nav-menu {
            list-style: none;
            flex-grow: 1;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1.25rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            color: #fff;
            box-shadow: 0 4px 20px var(--primary-glow);
        }

        .nav-link i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 280px;
            flex-grow: 1;
            padding: 2.5rem;
            max-width: 1400px;
        }

        /* Glassmorphism Card Style */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        /* Forms & Interactive Elements */
        input, select, textarea {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: #fff;
            width: 100%;
            outline: none;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
        }

        select optgroup {
            background-color: #1e293b; /* Dark background for optgroup */
            color: var(--primary); /* Distinguished color for labels */
            font-weight: 700;
            padding: 10px;
        }

        select option {
            background-color: #0f172a;
            color: #fff;
            padding: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            color: #fff;
            border: none;
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--primary-glow);
        }

        /* Progress Overlay */
        .progress-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(8px);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .progress-overlay.active {
            display: flex;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .auth-footer {
            margin-top: auto;
            border-top: 1px solid var(--glass-border);
            padding-top: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .user-info i {
            color: var(--text-muted);
        }

        @media (max-width: 1024px) {
            .sidebar { width: 80px; padding: 1.5rem 0.5rem; }
            .brand-logo span, .nav-link span, .user-info span { display: none; }
            .main-content { margin-left: 80px; padding: 1.5rem; }
            .brand-logo { justify-content: center; }
            .nav-link { justify-content: center; padding: 1rem; }
        }
    </style>
</head>
<body>
    <div class="layout-wrapper">
        <aside class="sidebar">
            <a href="/" class="brand-logo">
                <i class="fas fa-microphone-lines"></i>
                <span>{{ config('app.name') }}</span>
            </a>

            <nav class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('speech.index') }}" class="nav-link {{ Route::is('speech.*') ? 'active' : '' }}">
                        <i class="fas fa-comment-dots"></i>
                        <span>Speak Panel</span>
                    </a>
                </li>
                @auth
                <li class="nav-item">
                    <a href="{{ route('voices.index') }}" class="nav-link {{ Route::is('voices.*') ? 'active' : '' }}">
                        <i class="fas fa-microphone-lines"></i>
                        <span>Clone Voice</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.index') }}" class="nav-link {{ Route::is('settings.*') ? 'active' : '' }}">
                        <i class="fas fa-key"></i>
                        <span>API Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link {{ Route::is('profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user-gear"></i>
                        <span>Profile Log</span>
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link {{ Route::is('login') ? 'active' : '' }}">
                        <i class="fas fa-right-to-bracket"></i>
                        <span>Login</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('register') }}" class="nav-link {{ Route::is('register') ? 'active' : '' }}">
                        <i class="fas fa-user-plus"></i>
                        <span>Register</span>
                    </a>
                </li>
                @endauth
            </nav>

            <div class="auth-footer">
                @auth
                <div class="user-info">
                    <i class="fas fa-circle-user"></i>
                    <span>{{ Auth::user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link" style="background: none; border: none; width: 100%; cursor: pointer;">
                        <i class="fas fa-power-off"></i>
                        <span>Logout</span>
                    </button>
                </form>
                @endauth
            </div>
        </aside>

        <main class="main-content">
            @if(session('success'))
                <div class="glass-card" style="border-color: #22c55e; background: rgba(34, 197, 94, 0.1); margin-bottom: 2rem; padding: 1rem 2rem;">
                    <i class="fas fa-circle-check" style="color: #22c55e; margin-right: 0.5rem;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="glass-card" style="border-color: #ef4444; background: rgba(239, 68, 68, 0.1); margin-bottom: 2rem; padding: 1rem 2rem;">
                    <i class="fas fa-circle-exclamation" style="color: #ef4444; margin-right: 0.5rem;"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
            
            {{-- For Laravel Breeze default layouts if they use slots --}}
            @if(isset($slot))
                {{ $slot }}
            @endif
        </main>
    </div>

    <!-- Global Progress Overlay -->
    <div id="globalOverlay" class="progress-overlay">
        <div class="spinner"></div>
        <p id="overlayMessage">Processing...</p>
    </div>

    <!-- Ambient Background -->
    <canvas id="ambient-canvas"></canvas>

    <script>
        // Ambient Particles
        const canvas = document.getElementById('ambient-canvas');
        const ctx = canvas.getContext('2d');
        let particles = [];
        
        function initParticles() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            particles = [];
            for(let i=0; i<40; i++) {
                particles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    size: Math.random() * 2 + 1,
                    speedX: Math.random() * 0.5 - 0.25,
                    speedY: Math.random() * 0.5 - 0.25,
                    opacity: Math.random() * 0.5
                });
            }
        }

        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                p.x += p.speedX;
                p.y += p.speedY;
                if(p.x < 0) p.x = canvas.width;
                if(p.x > canvas.width) p.x = 0;
                if(p.y < 0) p.y = canvas.height;
                if(p.y > canvas.height) p.y = 0;
                
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(99, 102, 241, ${p.opacity})`;
                ctx.fill();
            });
            requestAnimationFrame(animateParticles);
        }

        window.addEventListener('resize', initParticles);
        initParticles();
        animateParticles();

        function showOverlay(message = 'Processing...') {
            document.getElementById('overlayMessage').textContent = message;
            document.getElementById('globalOverlay').classList.add('active');
        }

        function hideOverlay() {
            document.getElementById('globalOverlay').classList.remove('active');
        }
    </script>

    @stack('scripts')
</body>
</html>
