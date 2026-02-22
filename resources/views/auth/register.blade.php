<x-guest-layout>
    <div style="margin-bottom: 2rem; text-align: center;">
        <h2 style="font-size: 1.5rem; font-weight: 700;">Create Account</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Join the future of AI voice cloning</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" style="color: #ef4444; font-size: 0.8rem;" />
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: #ef4444; font-size: 0.8rem;" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 characters" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: #ef4444; font-size: 0.8rem;" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" style="color: #ef4444; font-size: 0.8rem;" />
        </div>

        <x-primary-button style="width: 100%; margin-bottom: 1.5rem;">
            {{ __('Create Account') }}
        </x-primary-button>

        <div style="text-align: center;">
            <p style="font-size: 0.9rem; color: var(--text-muted);">
                Already have an account? 
                <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">Sign in</a>
            </p>
        </div>
    </form>
</x-guest-layout>
