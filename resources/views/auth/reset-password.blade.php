<x-guest-layout>
    <div style="margin-bottom: 2rem; text-align: center;">
        <h2 style="font-size: 1.5rem; font-weight: 700;">Set New Password</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Choose a strong password for your account</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: #ef4444; font-size: 0.8rem;" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: #ef4444; font-size: 0.8rem;" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" style="color: #ef4444; font-size: 0.8rem;" />
        </div>

        <x-primary-button style="width: 100%;">
            {{ __('Update Password') }}
        </x-primary-button>
    </form>
</x-guest-layout>
