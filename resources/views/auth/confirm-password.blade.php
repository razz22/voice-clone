<x-guest-layout>
    <div style="margin-bottom: 2rem; text-align: center;">
        <h2 style="font-size: 1.5rem; font-weight: 700;">Confirm Password</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">
            {{ __('This is a secure area. Please confirm your password to continue.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-6">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: #ef4444; font-size: 0.8rem;" />
        </div>

        <x-primary-button style="width: 100%;">
            {{ __('Confirm Password') }}
        </x-primary-button>
    </form>
</x-guest-layout>
