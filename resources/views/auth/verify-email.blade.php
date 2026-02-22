<x-guest-layout>
    <div style="margin-bottom: 2rem; text-align: center;">
        <h2 style="font-size: 1.5rem; font-weight: 700;">Verify Email</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">
            {{ __('Thanks for signing up! Please verify your email by clicking the link we just sent.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; color: #22c55e; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.85rem;">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button style="width: 100%;">
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="width: 100%; padding: 0.75rem; background: none; border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-muted); font-weight: 600; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.borderColor=var(--primary); this.style.color='#fff'" onmouseout="this.style.borderColor='var(--glass-border)'; this.style.color='var(--text-muted)'">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
