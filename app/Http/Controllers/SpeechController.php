<?php

namespace App\Http\Controllers;

use App\Models\Voice;
use App\Services\ElevenLabsService;
use App\Services\FishAudioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SpeechController extends Controller
{
    protected ElevenLabsService $elevenlabs;
    protected FishAudioService $fishaudio;

    public function __construct(ElevenLabsService $elevenlabs, FishAudioService $fishaudio)
    {
        $this->elevenlabs = $elevenlabs;
        $this->fishaudio  = $fishaudio;
    }

    public function index()
    {
        // If logged in, show their voices. If guest, they only see system voices (which are hardcoded in the view).
        $voices = Auth::check() 
            ? Auth::user()->voices()->where('status', 'ready')->latest()->get() 
            : collect();

        return view('voices.speak', compact('voices'));
    }

    public function generate(Request $request)
    {
        Log::info('TTS Generation started', $request->all());

        $request->validate([
            'voice_id' => 'required|string',
            'text'     => 'required|string|min:1|max:2500',
        ]);

        $voiceId = $request->voice_id;
        $provider = null;
        $apiVoiceId = null;
        $user = Auth::user();

        // 1. Handle System/Test Voices (No Auth required for system, but maybe for test-api)
        $testVoices = [
            'test-fish-male'     => ['id' => '802e3bc2b27e49c2995d23ef70e6ac89', 'provider' => 'fish_audio'],
            'test-fish-female'   => ['id' => '8ef4a238714b45718ce04243307c57a7', 'provider' => 'fish_audio'],
            'test-eleven-male'   => ['id' => 'pNInz6obpgnu9it69ABg', 'provider' => 'elevenlabs'],
            'test-eleven-female' => ['id' => 'EXAVITQu4vr4xnSDxMaL', 'provider' => 'elevenlabs'],
        ];

        if (str_starts_with($voiceId, 'system-')) {
            // Handled by frontend synthesis, this route shouldn't even be hit for system-
            return response()->json(['error' => 'System voices are handled offline.'], 400);
        }

        if (isset($testVoices[$voiceId])) {
            $apiVoiceId = $testVoices[$voiceId]['id'];
            $provider = $testVoices[$voiceId]['provider'];
        } else {
            // 2. Handle Cloned Voices (Requires Auth)
            if (!Auth::check()) {
                return response()->json(['error' => 'Please login to use cloned voices.'], 401);
            }

            $voice = Voice::where('user_id', $user->id)->findOrFail($voiceId);
            if ($voice->status !== 'ready' || !$voice->elevenlabs_voice_id) {
                return response()->json(['error' => 'Selected voice is not ready.'], 422);
            }
            $apiVoiceId = $voice->elevenlabs_voice_id;
            $provider = $voice->provider;
        }

        try {
            if ($provider === 'elevenlabs') {
                $apiKey = $user ? ($user->elevenlabs_api_key ?: config('services.elevenlabs.api_key')) : config('services.elevenlabs.api_key');
                if (empty($apiKey)) throw new \Exception('No ElevenLabs API key found.');
                
                $options = [
                    'stability'  => $request->stability,
                    'similarity' => $request->similarity,
                    'style'      => $request->style,
                ];

                $audioData = $this->elevenlabs->setApiKey($apiKey)->textToSpeech($apiVoiceId, $request->text, $options);
            } else {
                $apiKey = $user ? ($user->fish_audio_api_key ?: config('services.fish_audio.api_key')) : config('services.fish_audio.api_key');
                if (empty($apiKey)) throw new \Exception('No Fish Audio API key found.');

                $audioData = $this->fishaudio->setApiKey($apiKey)->textToSpeech($apiVoiceId, $request->text);
            }

            return response($audioData, 200)
                ->header('Content-Type', 'audio/mpeg')
                ->header('Content-Disposition', 'inline; filename="speech.mp3"');
        } catch (\Exception $e) {
            Log::error('TTS Generation failed: ' . $e->getMessage());
            $errorMsg = $e->getMessage();
            if (str_contains($errorMsg, '401')) $errorMsg = "Invalid API Key in settings.";
            if (str_contains($errorMsg, '402')) $errorMsg = "Insufficient balance in AI account.";
            
            return response()->json(['error' => $errorMsg], 500);
        }
    }
}
