@extends('layouts.app')
@section('title', 'Clone a Voice')

@section('content')
<div class="page-header">
    <h1 class="page-title">🎤 Clone a Voice</h1>
    <p class="page-sub">Upload a voice sample and our AI will clone it instantly. 
        <a href="{{ route('settings.index') }}" style="color: var(--primary); font-weight: 600;">Check your API keys &rarr;</a>
    </p>
</div>

<div class="two-col">
    {{-- Upload Panel --}}
    <div>
        <div class="card">
            <div class="card-title">
                <span>🎙️</span> Upload Voice Sample
            </div>

            <form action="{{ route('voices.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <div class="form-group">
                    <label>AI Provider</label>
                    <select name="provider" id="providerSelect" required>
                        <option value="fish_audio">Fish Audio (FREE — recommended)</option>
                        <option value="elevenlabs" {{ old('provider') == 'elevenlabs' ? 'selected' : '' }}>ElevenLabs (PREMIUM — paid plan req.)</option>
                    </select>
                    <div id="providerHint" style="font-size: 11px; margin-top: 6px; color: var(--muted);">
                        Fish Audio offers a generous free tier for voice cloning. ElevenLabs requires a paid subscription.
                    </div>
                </div>

                <div class="form-group">
                    <label>Voice Name</label>
                    <input type="text" name="name" placeholder="e.g. My Voice, John, Product Demo…"
                           value="{{ old('name') }}" required maxlength="100">
                </div>

                <div class="form-group">
                    <label>Voice Description (optional)</label>
                    <input type="text" name="description" placeholder="e.g. Calm male narrator with British accent"
                           value="{{ old('description') }}" maxlength="500">
                </div>

                <div class="form-group">
                    <label>Audio Sample</label>
                    <div class="file-zone" id="fileZone">
                        <input type="file" name="sample" accept=".mp3,.wav,.ogg,.m4a,.flac,.webm"
                               required id="sampleInput">
                        <div class="file-zone-icon">🎵</div>
                        <div class="file-zone-text">
                            Drop your audio here or <span>browse</span>
                        </div>
                        <div class="file-zone-text" style="margin-top: 6px; font-size: 12px;">
                            MP3, WAV, OGG, M4A · Max 20 MB · Recommended: 10–30 sec of clear speech
                        </div>
                        <div class="file-name" id="fileName"></div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn" style="width: 100%;">
                    <span id="submitSpinner" class="spinner"></span>
                    <span id="submitText">🚀 Clone Voice</span>
                </button>
            </form>
        </div>

        {{-- Tips card --}}
        <div class="card" style="margin-top: 20px;">
            <div class="card-title"><span>💡</span> Tips for Best Results</div>
            <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px;">
                <li style="font-size: 13px; color: var(--muted); display: flex; gap: 8px;">
                    <span>🎙️</span> Record in a quiet room with no background noise
                </li>
                <li style="font-size: 13px; color: var(--muted); display: flex; gap: 8px;">
                    <span>⏱️</span> Upload at least 15 seconds of audio (Fish Audio is very fast!)
                </li>
                <li style="font-size: 13px; color: var(--muted); display: flex; gap: 8px;">
                    <span>🎯</span> Speak naturally and consistently throughout the sample
                </li>
            </ul>
        </div>
    </div>

    {{-- Voice List --}}
    <div>
        <div class="card">
            <div class="card-title" style="justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span>🎭</span> Your Cloned Voices
                </div>
                @if($voices->count() > 0)
                    <span style="font-size: 12px; color: var(--muted);">{{ $voices->count() }} voice{{ $voices->count() === 1 ? '' : 's' }}</span>
                @endif
            </div>

            @if($voices->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">🎈</div>
                    <div class="empty-text">No voices cloned yet.<br>Upload your first sample to get started!</div>
                </div>
            @else
                <div class="voice-grid">
                    @foreach($voices as $voice)
                    <div class="voice-card">
                        <div class="voice-card-header">
                            <div class="voice-avatar">{{ $voice->provider === 'elevenlabs' ? '💎' : '🐟' }}</div>
                            <div>
                                @if($voice->status === 'ready')
                                    <span class="badge badge-ready">
                                        <span class="badge-dot"></span> Ready
                                    </span>
                                @elseif($voice->status === 'creating')
                                    <span class="badge badge-creating">
                                        <span class="badge-dot"></span> Creating
                                    </span>
                                @else
                                    <span class="badge badge-failed">
                                        <span class="badge-dot"></span> Failed
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="voice-name">{{ $voice->name }}</div>
                        <div style="font-size: 10px; color: var(--muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em;">
                            {{ str_replace('_', ' ', $voice->provider) }}
                        </div>

                        @if($voice->description)
                            <div class="voice-desc">{{ $voice->description }}</div>
                        @endif

                        <div style="margin-top: 14px; display: flex; gap: 8px; align-items: center; justify-content: space-between;">
                            @if($voice->status === 'ready')
                            <a href="{{ route('speech.index') }}?voice={{ $voice->id }}"
                               class="btn btn-play btn-sm" style="font-size: 12px; padding: 7px 14px; background: linear-gradient(135deg, var(--cyan), #0891b2); box-shadow: none;">
                                ▶ Speak
                            </a>
                            @else
                                <span></span>
                            @endif

                            <form action="{{ route('voices.destroy', $voice) }}" method="POST" class="delete-form"
                                  onsubmit="return confirm('Delete voice \'{{ addslashes($voice->name) }}\'? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑️ Delete</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            @if($voices->where('status', 'ready')->count() > 0)
            <div style="margin-top: 20px; text-align: center;">
                <a href="{{ route('speech.index') }}" class="btn btn-play">
                    ▶️ Go to Speak Panel
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const providerSelect = document.getElementById('providerSelect');
    const providerHint   = document.getElementById('providerHint');
    const fileZone       = document.getElementById('fileZone');
    const fileInput      = document.getElementById('sampleInput');
    const fileLabel      = document.getElementById('fileName');
    const uploadForm     = document.getElementById('uploadForm');
    const submitBtn      = document.getElementById('submitBtn');
    const submitText     = document.getElementById('submitText');
    const submitSpinner  = document.getElementById('submitSpinner');
    const overlay        = document.getElementById('progressOverlay');

    providerSelect.addEventListener('change', () => {
        if (providerSelect.value === 'elevenlabs') {
            providerHint.innerHTML = '<span style="color: var(--yellow)">⚠️ Paid Starter Plan or higher required on ElevenLabs for cloning.</span>';
        } else {
            providerHint.innerHTML = '<span style="color: var(--green)">✅ Fish Audio offers free voice cloning on their free tier.</span>';
        }
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files[0]) {
            fileLabel.textContent = '📎 ' + fileInput.files[0].name;
        }
    });

    fileZone.addEventListener('dragover', e => { e.preventDefault(); fileZone.classList.add('drag-over'); });
    fileZone.addEventListener('dragleave', () => fileZone.classList.remove('drag-over'));
    fileZone.addEventListener('drop', e => {
        e.preventDefault();
        fileZone.classList.remove('drag-over');
        if (e.dataTransfer.files[0]) {
            fileInput.files = e.dataTransfer.files;
            fileLabel.textContent = '📎 ' + e.dataTransfer.files[0].name;
        }
    });

    uploadForm.addEventListener('submit', () => {
        submitBtn.disabled = true;
        submitSpinner.classList.add('active');
        submitText.textContent = 'Cloning…';
        overlay.classList.add('active');
    });
</script>
@endpush
