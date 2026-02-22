<?php

namespace App\Http\Controllers;

use App\Models\Voice;
use App\Services\ElevenLabsService;
use App\Services\FishAudioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VoiceController extends Controller
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
        $voices = Auth::user()->voices()->latest()->get();
        return view('voices.index', compact('voices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'provider'    => 'required|in:elevenlabs,fish_audio',
            'sample'      => 'required|file|mimes:mp3,wav,ogg,m4a,flac,webm|max:20480',
            'description' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        // Store the audio file
        $path = $request->file('sample')->store('voice-samples', 'local');
        $fullPath = Storage::disk('local')->path($path);

        // Create the DB record with "creating" status
        $voice = Voice::create([
            'name'        => $request->name,
            'user_id'     => $user->id,
            'provider'    => $request->provider,
            'description' => $request->description ?? '',
            'sample_path' => $path,
            'status'      => 'creating',
        ]);

        try {
            if ($request->provider === 'elevenlabs') {
                $apiKey = $user->elevenlabs_api_key ?: config('services.elevenlabs.api_key');
                if (empty($apiKey)) throw new \Exception('No ElevenLabs API key found in your settings.');

                $voiceId = $this->elevenlabs->setApiKey($apiKey)->cloneVoice(
                    $request->name,
                    $fullPath,
                    $request->description ?? ''
                );
            } else {
                $apiKey = $user->fish_audio_api_key ?: config('services.fish_audio.api_key');
                if (empty($apiKey)) throw new \Exception('No Fish Audio API key found in your settings.');

                $voiceId = $this->fishaudio->setApiKey($apiKey)->cloneVoice(
                    $request->name,
                    $fullPath,
                    $request->description ?? ''
                );
            }

            $voice->update([
                'elevenlabs_voice_id' => $voiceId,
                'status'              => 'ready',
            ]);

            return redirect()->route('voices.index')
                ->with('success', "🎉 Voice \"{$request->name}\" ({$request->provider}) has been cloned successfully!");
        } catch (\Exception $e) {
            $voice->update(['status' => 'failed']);

            $msg = $e->getMessage();
            if (str_contains($msg, 'paid_plan_required')) {
                $msg = "ElevenLabs requires a PAID plan for voice cloning. Please use Fish Audio (Free) instead!";
            } elseif (str_contains($msg, '401')) {
                $msg = "Invalid API Key for " . ucfirst($request->provider) . ". Please check your API Settings.";
            }

            return redirect()->route('voices.index')
                ->with('error', 'Voice cloning failed: ' . $msg);
        }
    }

    public function destroy(Voice $voice)
    {
        // Enforce ownership
        if ($voice->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();

        try {
            if ($voice->elevenlabs_voice_id) {
                if ($voice->provider === 'elevenlabs') {
                    $apiKey = $user->elevenlabs_api_key ?: config('services.elevenlabs.api_key');
                    if ($apiKey) $this->elevenlabs->setApiKey($apiKey)->deleteVoice($voice->elevenlabs_voice_id);
                } else {
                    $apiKey = $user->fish_audio_api_key ?: config('services.fish_audio.api_key');
                    if ($apiKey) $this->fishaudio->setApiKey($apiKey)->deleteVoice($voice->elevenlabs_voice_id);
                }
            }
        } catch (\Exception $e) {
            // Ignore API errors on delete
        }

        if ($voice->sample_path) {
            Storage::disk('local')->delete($voice->sample_path);
        }

        $voice->delete();

        return redirect()->route('voices.index')
            ->with('success', "Voice \"{$voice->name}\" deleted.");
    }
}
