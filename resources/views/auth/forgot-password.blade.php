<x-guest-layout>
    <div style="margin-bottom: 2rem; text-align: center;">
        <h2 style="font-size: 1.5rem; font-weight: 700;">Reset Password</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">
            {{ __('Forgot your password? No problem. We\'ll email you a reset link.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-6">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: #ef4444; font-size: 0.8rem;" />
        </div>

        <x-primary-button style="width: 100%;">
            {{ __('Email Reset Link') }}
        </x-primary-button>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600; text-decoration: none; font-size: 0.9rem;">
                Back to Sign In
            </a>
        </div>
    </form>
</x-guest-layout>
