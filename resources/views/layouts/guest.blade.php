<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="background-color: #0f172a !important;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --primary: #6366f1;
                --text-main: #f8fafc;
                --text-muted: #94a3b8;
                --bg-input: #1e293b;
            }
            body { 
                background: #0f172a !important; 
                color: #f8fafc !important;
                margin: 0; padding: 0;
                min-height: 100vh;
                display: flex; flex-direction: column; align-items: center; justify-content: center;
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body>
        <a href="/" style="font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 700; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; color: #ffffff !important; text-decoration: none;">
            <i class="fas fa-microphone-lines" style="color: #6366f1;"></i>
            <span>{{ config('app.name') }}</span>
        </a>

        <div class="glass-card" style="background: rgba(30, 41, 59, 0.98) !important; backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1) !important; border-radius: 24px; padding: 2.5rem; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8) !important; width: 90%; max-width: 450px;">
            {{ $slot }}
        </div>

        <!-- Nuclear Fix for Input Visibility -->
        <style>
            input, select, textarea {
                background-color: #1e293b !important;
                color: #ffffff !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                padding: 0.75rem 1rem !important;
                width: 100% !important;
                border-radius: 12px !important;
                outline: none !important;
                font-size: 0.95rem !important;
                transition: all 0.3s ease !important;
                margin-top: 0.5rem !important;
            }
            input:focus, select:focus, textarea:focus {
                border-color: #6366f1 !important;
                box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
            }
            input::placeholder {
                color: rgba(255, 255, 255, 0.3) !important;
            }
            label {
                color: #94a3b8 !important;
            }
            /* Chrome/Safari Autofill override */
            input:-webkit-autofill,
            input:-webkit-autofill:hover, 
            input:-webkit-autofill:focus {
                -webkit-text-fill-color: white !important;
                -webkit-box-shadow: 0 0 0px 1000px #1e293b inset !important;
            }
        </style>
    </body>
</html>
