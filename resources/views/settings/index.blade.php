@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <header style="margin-bottom: 2.5rem; display: flex; align-items: center; gap: 1rem;">
        <div style="background: rgba(99, 102, 241, 0.1); width: 60px; height: 60px; border-radius: 20px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-gear" style="font-size: 1.5rem; color: var(--primary);"></i>
        </div>
        <div>
            <h1 style="font-size: 2rem; font-weight: 700;">AI Provider Settings</h1>
            <p style="color: var(--text-muted);">Manage your personal API keys for cloning and speech.</p>
        </div>
    </header>

    <div class="glass-card">
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-fish" style="color: #3b82f6;"></i>
                    Fish Audio (Free Alternative)
                </h3>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-muted);">Fish Audio API Key</label>
                    <input type="password" name="fish_audio_api_key" value="{{ $user->fish_audio_api_key }}" placeholder="fish_...">
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">
                        Get your key from <a href="https://fish.audio/go-api-key" target="_blank" style="color: var(--primary);">fish.audio</a>.
                    </p>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 2.5rem 0;">

            <div style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-gem" style="color: #8b5cf6;"></i>
                    ElevenLabs (Premium)
                </h3>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-muted);">ElevenLabs API Key</label>
                    <input type="password" name="elevenlabs_api_key" value="{{ $user->elevenlabs_api_key }}" placeholder="sk_...">
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">
                        Get your key from <a href="https://elevenlabs.io" target="_blank" style="color: var(--primary);">elevenlabs.io</a>.
                    </p>
                </div>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%;">
                <i class="fas fa-floppy-disk"></i>
                Save API Keys
            </button>
        </form>
    </div>

    <div style="margin-top: 2rem; padding: 1.5rem; border-radius: 16px; background: rgba(99, 102, 241, 0.05); border: 1px dashed var(--glass-border);">
        <p style="font-size: 0.9rem; color: var(--text-muted); text-align: center;">
            <i class="fas fa-shield-halved" style="margin-right: 0.5rem;"></i>
            Your keys are stored securely. We only use them to communicate with the AI providers on your behalf.
        </p>
    </div>
</div>
@endsection
