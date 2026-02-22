@extends('layouts.app')
@section('title', 'Speak with Cloned Voice')

@push('styles')
<style>
    /* Premium Voice Interface Overhaul */
    .speak-container {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 2rem;
        margin-top: 1rem;
        position: relative;
    }

    @media (max-width: 1200px) {
        .speak-container { grid-template-columns: 1fr; }
    }

    /* Glass Cards */
    .premium-card {
        background: rgba(30, 41, 59, 0.5);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 28px;
        padding: 2.25rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        height: min-content;
        transition: transform 0.3s ease, border-color 0.3s ease;
    }

    .premium-card:hover { border-color: rgba(255, 255, 255, 0.15); }

    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #fff;
    }

    .section-title i {
        color: var(--primary);
        filter: drop-shadow(0 0 8px var(--primary-glow));
    }

    /* Voice Grid */
    .voice-select-layout {
        display: grid;
        gap: 1.5rem;
    }

    #voiceSelect {
        width: 100%;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 1.25rem;
        color: #fff;
        font-size: 1rem;
        cursor: pointer;
        outline: none;
        transition: all 0.3s;
    }

    #voiceSelect:focus { border-color: var(--primary); box-shadow: 0 0 15px var(--primary-glow); }

    /* Modern Textarea */
    .speech-box {
        position: relative;
        margin-top: 2rem;
    }

    .speech-input {
        width: 100%;
        background: rgba(15, 23, 42, 0.4);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 1.5rem;
        color: #fff;
        font-family: inherit;
        font-size: 1.1rem;
        line-height: 1.6;
        resize: none;
        min-height: 250px;
        transition: all 0.3s ease;
    }

    .speech-input:focus {
        background: rgba(15, 23, 42, 0.6);
        border-color: var(--primary);
        outline: none;
    }

    /* Advanced Settings */
    .advanced-toggle {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-muted);
        font-size: 0.85rem;
        cursor: pointer;
        margin-top: 1rem;
        font-weight: 600;
        transition: color 0.3s;
    }

    .advanced-toggle:hover { color: #fff; }

    .advanced-panel {
        display: none;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--glass-border);
    }
    
    .advanced-panel.active { display: grid; }

    @media (max-width: 640px) { 
        .advanced-panel { grid-template-columns: 1fr; }
    }

    .control-group { display: flex; flex-direction: column; gap: 0.75rem; }

    .control-info {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-muted);
    }

    .control-val { color: var(--primary); font-family: 'Outfit', sans-serif; font-weight: 700; }

    /* Range Input */
    input[type=range] {
        -webkit-appearance: none;
        width: 100%;
        height: 8px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        outline: none;
    }

    input[type=range]::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 20px;
        height: 20px;
        background: var(--primary);
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 0 12px var(--primary-glow);
        border: 2px solid #fff;
        transition: transform 0.2s;
    }

    input[type=range]::-webkit-slider-thumb:hover { transform: scale(1.2); }

    /* Right Column Widgets */
    .widget { margin-bottom: 2rem; }

    /* Visualizer */
    .visualizer-card {
        background: #000;
        border-radius: 24px;
        height: 140px;
        overflow: hidden;
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    #visualizer { width: 100%; height: 100%; }

    /* Play Button */
    .btn-generate {
        width: 100%;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff;
        border: none;
        border-radius: 18px;
        padding: 1.25rem;
        font-size: 1.1rem;
        font-weight: 700;
        font-family: 'Outfit', sans-serif;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        transition: all 0.3s;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
    }

    .btn-generate:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(99, 102, 241, 0.5);
    }

    .btn-generate:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* History List */
    .history-card { padding: 1.75rem; border-radius: 28px; }
    
    .history-scroll {
        max-height: 480px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .history-scroll::-webkit-scrollbar { width: 4px; }
    .history-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

    .history-item {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: 0.3s;
    }

    .history-item:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(255, 255, 255, 0.15);
    }

    .history-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .history-voice { font-weight: 700; font-family: 'Outfit', sans-serif; font-size: 0.9rem; color: #fff; }
    .history-time { font-size: 0.75rem; color: var(--text-muted); }

    .history-text {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .history-actions {
        display: flex;
        gap: 0.75rem;
    }

    .action-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        cursor: pointer;
        text-decoration: none;
        border: none;
    }

    .action-circle:hover { background: var(--primary); box-shadow: 0 0 10px var(--primary-glow); }

    /* Category Tabs */
    .category-tabs {
        display: flex;
        gap: 0.6rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .category-btn {
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        color: var(--text-muted);
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .category-btn.active {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    #sample-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    .sample-item {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.04);
        border-radius: 14px;
        padding: 0.85rem 1.1rem;
        font-size: 0.85rem;
        color: var(--text-muted);
        cursor: pointer;
        transition: 0.3s;
        line-height: 1.4;
    }

    .sample-item:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.15);
    }
</style>
@endpush

@section('content')
<div class="page-header" style="margin-bottom: 3rem;">
    <h1 class="page-title" style="font-size: 2.5rem; letter-spacing: -0.02em;">Digital Voice Forge</h1>
    <p class="page-sub" style="font-size: 1.1rem;">Animate your text with pinpoint precision and premium acoustic clarity.</p>
</div>

@if($voices->isEmpty())
    <div class="premium-card" style="text-align: center; padding: 5rem 2rem;">
        <div style="font-size: 4rem; margin-bottom: 2rem;">🎙️</div>
        <h2 style="font-family: 'Outfit', sans-serif; margin-bottom: 1rem;">No Voices Found</h2>
        <p style="color: var(--text-muted); margin-bottom: 2rem; max-width: 400px; margin-left: auto; margin-right: auto;">
            You need to clone a voice before you can start generating speech. It only takes 30 seconds!
        </p>
        <a href="{{ route('voices.index') }}" class="btn-generate" style="max-width: 280px; margin: 0 auto; text-decoration: none;">
            Create Your First Voice
        </a>
    </div>
@else
<div class="speak-container">
    {{-- Left: Input & Config --}}
    <div class="voice-select-layout">
        <div class="premium-card">
            <div class="section-head">
                <div class="section-title"><i class="fas fa-sliders-h"></i> Configuration</div>
                @auth
                <div style="font-size: 0.75rem; color: var(--text-muted); padding: 4px 12px; background: rgba(255,255,255,0.05); border-radius: 20px;">
                    <i class="fas fa-user-shield"></i> Personal Account
                </div>
                @endauth
            </div>

            <div class="form-group">
                <label style="font-size: 0.9rem; font-weight: 700; color: #fff; margin-bottom: 1rem; display: block;">Choose Voice Engine</label>
                <select id="voiceSelect">
                    @guest
                        <option value="">— Sign in to access Private Voices —</option>
                    @else
                        <option value="">— Select Target Voice —</option>
                    @endguest

                    @if($voices->isNotEmpty())
                    <optgroup label="Your Cloned Identities">
                        @foreach($voices as $voice)
                        <option value="{{ $voice->id }}" data-provider="{{ $voice->provider }}">
                            {{ $voice->provider === 'elevenlabs' ? '💎' : '🐟' }} {{ $voice->name }}
                        </option>
                        @endforeach
                    </optgroup>
                    @endif

                    <optgroup label="Experimental AI">
                        <option value="test-fish-male" data-provider="fish_audio">🐟 [Test] Energetic Male</option>
                        <option value="test-fish-female" data-provider="fish_audio">🐟 [Test] E-Girl Prompt</option>
                        <option value="test-eleven-male" data-provider="elevenlabs">💎 [Test] Adam (Premier)</option>
                    </optgroup>

                    <optgroup label="Local Processing (Offline)">
                        <option value="system-natural" data-provider="system">💻 System Standard</option>
                        <option value="system-robotic" data-provider="system">🤖 Harmonic Bot</option>
                    </optgroup>
                </select>
            </div>

            <div class="speech-box">
                <label style="font-size: 0.9rem; font-weight: 700; color: #fff; margin-bottom: 1rem; display: block;">Target Script</label>
                <textarea class="speech-input" id="speechText" placeholder="Enter the text you want the AI to synthesize..."></textarea>
                <div id="charCounter" style="position: absolute; bottom: 1.5rem; right: 1.5rem; font-size: 0.75rem; color: var(--text-muted); font-weight: 700;">
                    0 / 2,500
                </div>
            </div>

            <div class="advanced-toggle" id="toggleAdvanced">
                <i class="fas fa-cog"></i> <span>Show Advanced Calibration</span>
            </div>

            <div class="advanced-panel" id="advancedPanel">
                <div class="control-group">
                    <div class="control-info">
                        <span>Stability</span>
                        <span class="control-val"><span id="stabilityVal">50</span>%</span>
                    </div>
                    <input type="range" class="modern-slider" id="stabilitySlider" min="0" max="100" value="50">
                    <div style="font-size: 10px; color: var(--text-muted);">Lower = More variable, Higher = More consistent</div>
                </div>
                <div class="control-group">
                    <div class="control-info">
                        <span>Similarity Boost</span>
                        <span class="control-val"><span id="similarityVal">75</span>%</span>
                    </div>
                    <input type="range" class="modern-slider" id="similaritySlider" min="0" max="100" value="75">
                    <div style="font-size: 10px; color: var(--text-muted);">Strength of clone resemblance</div>
                </div>
                <div class="control-group">
                    <div class="control-info">
                        <span>Style Exaggeration</span>
                        <span class="control-val"><span id="styleVal">0</span>%</span>
                    </div>
                    <input type="range" class="modern-slider" id="styleSlider" min="0" max="100" value="0">
                </div>
                <div class="control-group">
                    <div class="control-info">
                        <span>Clarity / Sharpness</span>
                        <span class="control-val">Auto</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #fff;">
                        <input type="checkbox" id="clarityCheck" checked> <span>HD Processing</span>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 2rem;">
                <button class="btn-generate" id="playBtn" disabled>
                    <i class="fas fa-bolt" id="pIco"></i>
                    <span id="pTxt">Synthesize & Play</span>
                </button>
            </div>
        </div>

        <div class="premium-card">
            <div class="section-title"><i class="fas fa-book-open"></i> Sample Library</div>
            <div class="category-tabs">
                <button class="category-btn active" data-cat="intro">Introductions</button>
                <button class="category-btn" data-cat="pro">Professional</button>
                <button class="category-btn" data-cat="creative">Creative</button>
                <button class="category-btn" data-cat="emotional">Emotional</button>
                <button class="category-btn" data-cat="short">Quick Tests</button>
            </div>
            <div id="sample-grid">
                <!-- Dynamically populated -->
            </div>
        </div>
    </div>

    {{-- Right: Visualizer & History --}}
    <div>
        <div class="premium-card widget" style="padding: 1.5rem;">
            <div class="section-title" style="margin-bottom: 1.5rem;"><i class="fas fa-wave-square"></i> Audio Output</div>
            
            <div class="visualizer-box">
                <canvas id="visualizer"></canvas>
                <div id="idleVisual" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.1); font-size: 2rem;">
                    <i class="fas fa-volume-mute"></i>
                </div>
            </div>

            <audio id="audioPlayer" controls style="width: 100%; display: none;"></audio>

            <div id="playbackControls" style="display: none; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                <button class="action-circle" id="replayBtn" style="width: 100%; border-radius: 14px; font-size: 0.9rem;">
                    <i class="fas fa-redo"></i> &nbsp; Replay
                </button>
                <a id="downloadBtn" class="action-circle" style="width: 100%; border-radius: 14px; font-size: 0.9rem;" download="speech.mp3">
                    <i class="fas fa-download"></i> &nbsp; Export
                </a>
            </div>
        </div>

        <div class="premium-card history-card">
            <div class="section-head">
                <div class="section-title"><i class="fas fa-history"></i> Recent Clips</div>
                <button id="clearHistory" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 0.7rem;">Clear All</button>
            </div>
            
            <div class="history-scroll" id="historyList">
                <div style="text-align: center; color: var(--text-muted); padding: 2rem 0; font-size: 0.8rem;">
                    Your latest 20 generations will appear here locally.
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    // Constants & State
    const samples = {
        intro: [
            "Hello! I am your AI-powered vocal identity. I can read anything you type with natural emotion.",
            "Welcome to the portal. This is a demonstration of high-fidelity voice cloning technology.",
            "Testing, one two three. Analyzing vocal patterns for perfect synthesis."
        ],
        pro: [
            "Our quarterly performance exceeded expectations, driven by strong growth in the enterprise sector.",
            "Please find the executive summary attached to this correspondence for your immediate review.",
            "Good morning and welcome to today's keynote presentation on the future of generative artificial intelligence."
        ],
        creative: [
            "In a hole in the ground there lived a hobbit. Not a nasty, dirty, wet hole, filled with the ends of worms and an oozy smell...",
            "The stars sparkled like diamonds across the velvet sky, whispering secrets of galaxies far away.",
            "Call me Ishmael. Some years ago—never mind how long precisely—having little or no money in my purse..."
        ],
        emotional: [
            "I've seen things you people wouldn't believe. Attack ships on fire off the shoulder of Orion.",
            "It's not about how hard you hit, it's about how hard you can get hit and keep moving forward.",
            "I love you. More than I have ever loved anyone. And I will wait for you, no matter how long it takes."
        ],
        short: [
            "Quick test of the system.",
            "The quick brown fox jumps over the lazy dog.",
            "Voice cloning is awesome!"
        ]
    };

    // DOM Elements
    const elements = {
        voice: document.getElementById('voiceSelect'),
        text: document.getElementById('speechText'),
        playBtn: document.getElementById('playBtn'),
        pTxt: document.getElementById('pTxt'),
        pIco: document.getElementById('pIco'),
        charCount: document.getElementById('charCounter'),
        visualizer: document.getElementById('visualizer'),
        idleVisual: document.getElementById('idleVisual'),
        audio: document.getElementById('audioPlayer'),
        playback: document.getElementById('playbackControls'),
        history: document.getElementById('historyList'),
        advanced: document.getElementById('advancedPanel'),
        toggleAdv: document.getElementById('toggleAdvanced'),
        sampleGrid: document.getElementById('sample-grid'),
        stability: document.getElementById('stabilitySlider'),
        stabilityVal: document.getElementById('stabilityVal'),
        similarity: document.getElementById('similaritySlider'),
        similarityVal: document.getElementById('similarityVal'),
        style: document.getElementById('styleSlider'),
        styleVal: document.getElementById('styleVal')
    };

    let audioContext, analyser, dataArray, animationId;
    let localHistory = JSON.parse(localStorage.getItem('vf_history') || '[]');

    // Setup Samples
    function setSamples(cat) {
        elements.sampleGrid.innerHTML = '';
        samples[cat].forEach(s => {
            const div = document.createElement('div');
            div.className = 'sample-item';
            div.textContent = s;
            div.onclick = () => {
                elements.text.value = s;
                syncForm();
            };
            elements.sampleGrid.appendChild(div);
        });
    }

    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.onclick = () => {
            document.querySelector('.category-btn.active').classList.remove('active');
            btn.classList.add('active');
            setSamples(btn.dataset.cat);
        };
    });

    // Setup Visualizer
    function initVisualizer() {
        if (!audioContext) {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            analyser = audioContext.createAnalyser();
            const source = audioContext.createMediaElementSource(elements.audio);
            source.connect(analyser);
            analyser.connect(audioContext.destination);
            analyser.fftSize = 256;
            dataArray = new Uint8Array(analyser.frequencyBinCount);
        }
    }

    function drawVisualizer() {
        animationId = requestAnimationFrame(drawVisualizer);
        analyser.getByteFrequencyData(dataArray);
        const canvas = elements.visualizer;
        const ctx = canvas.getContext('2d');
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
        
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        const barWidth = (canvas.width / dataArray.length) * 2.5;
        let x = 0;

        for (let i = 0; i < dataArray.length; i++) {
            const barHeight = (dataArray[i] / 255) * canvas.height;
            const r = 99 + (i * 2);
            const g = 102;
            const b = 241;
            
            ctx.fillStyle = `rgba(${r}, ${g}, ${b}, ${dataArray[i]/255})`;
            ctx.fillRect(x, canvas.height - barHeight, barWidth, barHeight);
            x += barWidth + 2;
        }
    }

    // History Logic
    function updateHistoryUI() {
        if (localHistory.length === 0) {
            elements.history.innerHTML = '<div style="text-align: center; color: var(--text-muted); padding: 2rem 0; font-size: 0.8rem;">No clips yet. Generate one!</div>';
            return;
        }
        elements.history.innerHTML = '';
        localHistory.forEach((item, idx) => {
            const div = document.createElement('div');
            div.className = 'history-item';
            div.innerHTML = `
                <div class="history-top">
                    <span class="history-voice">${item.voice}</span>
                    <span class="history-time">${new Date(item.time).toLocaleTimeString()}</span>
                </div>
                <div class="history-text">${item.text}</div>
                <div class="history-actions">
                    <button class="action-circle" onclick="playHistory(${idx})"><i class="fas fa-play"></i></button>
                    <a href="${item.blobUrl}" class="action-circle" download="clip-${idx}.mp3"><i class="fas fa-download"></i></a>
                </div>
            `;
            elements.history.appendChild(div);
        });
    }

    function addToHistory(blob, text, voice) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const item = {
                blobUrl: e.target.result,
                text: text,
                voice: voice,
                time: Date.now()
            };
            localHistory.unshift(item);
            if (localHistory.length > 20) localHistory.pop();
            localStorage.setItem('vf_history', JSON.stringify(localHistory));
            updateHistoryUI();
        };
        reader.readAsDataURL(blob);
    }

    window.playHistory = (idx) => {
        const item = localHistory[idx];
        elements.audio.src = item.blobUrl;
        elements.audio.play();
        elements.idleVisual.style.display = 'none';
        elements.playback.style.display = 'grid';
        initVisualizer();
        if (!animationId) drawVisualizer();
    };

    // UI Logic
    function syncForm() {
        const canPlay = elements.voice.value && elements.text.value.trim().length > 0;
        elements.playBtn.disabled = !canPlay;
        elements.charCount.textContent = `${elements.text.value.length.toLocaleString()} / 2,500`;
        elements.charCount.style.color = elements.text.value.length > 2000 ? '#ef4444' : 'var(--text-muted)';
    }

    elements.voice.onchange = syncForm;
    elements.text.oninput = syncForm;
    elements.toggleAdv.onclick = () => elements.advanced.classList.toggle('active');
    
    // Sliders
    elements.stability.oninput = () => elements.stabilityVal.textContent = elements.stability.value;
    elements.similarity.oninput = () => elements.similarityVal.textContent = elements.similarity.value;
    elements.style.oninput = () => elements.styleVal.textContent = elements.style.value;

    document.getElementById('clearHistory').onclick = () => {
        localHistory = [];
        localStorage.removeItem('vf_history');
        updateHistoryUI();
    };

    // Core Speech Generation
    async function generate() {
        const voiceId = elements.voice.value;
        const text = elements.text.value.trim();
        const voiceName = elements.voice.options[elements.voice.selectedIndex].text.replace(/^[💎🐟💻🤖]\s/, '');

        // Lock UI
        elements.playBtn.disabled = true;
        elements.pTxt.textContent = 'Calibrating Acoustic Model...';
        elements.pIco.className = 'fas fa-spinner fa-spin';
        elements.idleVisual.style.display = 'none';

        try {
            if (voiceId.startsWith('system-')) {
                // System synthesis fallback
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.onstart = () => {
                    initVisualizer();
                    if (!animationId) drawVisualizer();
                };
                window.speechSynthesis.speak(utterance);
                
                // Simulate success UI
                elements.pTxt.textContent = 'Synthesize & Play';
                elements.pIco.className = 'fas fa-bolt';
                elements.playBtn.disabled = false;
                return;
            }

            const res = await fetch('{{ route("speech.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    voice_id: voiceId,
                    text: text,
                    stability: elements.stability.value / 100,
                    similarity: elements.similarity.value / 100,
                    style: elements.style.value / 100
                })
            });

            if (!res.ok) {
                const err = await res.json();
                throw new Error(err.error || 'Interface Calibration failed');
            }

            const blob = await res.blob();
            const url = URL.createObjectURL(blob);
            
            elements.audio.src = url;
            elements.playback.style.display = 'grid';
            document.getElementById('downloadBtn').href = url;
            
            initVisualizer();
            if (!animationId) drawVisualizer();
            
            elements.audio.play();
            addToHistory(blob, text, voiceName);

        } catch (e) {
            alert(e.message);
        } finally {
            elements.playBtn.disabled = false;
            elements.pTxt.textContent = 'Synthesize & Play';
            elements.pIco.className = 'fas fa-bolt';
            syncForm();
        }
    }

    elements.playBtn.onclick = generate;
    document.getElementById('replayBtn').onclick = () => elements.audio.play();

    // Initial load
    setSamples('intro');
    updateHistoryUI();
    syncForm();
</script>
@endpush
