<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div style="margin-bottom: 2rem; text-align: center;">
        <h2 style="font-size: 1.5rem; font-weight: 700;">Welcome Back</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Sign in to access your AI voices</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: #ef4444; font-size: 0.8rem;" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: #ef4444; font-size: 0.8rem;" />
        </div>

        <!-- Remember Me -->
        <div class="mb-6 flex items-center">
            <input id="remember_me" type="checkbox" name="remember" style="width: 18px; height: 18px; margin-right: 10px; cursor: pointer;">
            <label for="remember_me" style="font-size: 0.85rem; color: var(--text-muted); cursor: pointer;">{{ __('Keep me signed in') }}</label>
        </div>

        <x-primary-button style="width: 100%; margin-bottom: 1.5rem;">
            {{ __('Sign In') }}
        </x-primary-button>

        <div style="text-align: center; display: flex; flex-direction: column; gap: 0.75rem;">
            @if (Route::has('password.request'))
                <a style="font-size: 0.8rem; color: var(--text-muted); text-decoration: none;" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <p style="font-size: 0.9rem; color: var(--text-muted);">
                Don't have an account? 
                <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">Register now</a>
            </p>
        </div>
    </form>
</x-guest-layout>
