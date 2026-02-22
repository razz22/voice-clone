<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FishAudioService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.fish.audio';

    public function __construct()
    {
        $this->apiKey = config('services.fish_audio.api_key') ?? '';
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Clone a voice by uploading an audio file.
     * Returns the model_id (voice_id) from Fish Audio.
     */
    public function cloneVoice(string $name, string $filePath, string $description = ''): string
    {
        $response = Http::withoutVerifying()
            ->timeout(60)
            ->withToken($this->apiKey)
            ->attach('voices', file_get_contents($filePath), basename($filePath))
            ->post($this->baseUrl . '/model', [
                'title'       => $name,
                'description' => $description,
                'type'        => 'tts',
                'train_mode'  => 'fast',
                'visibility'  => 'private',
            ]);

        if (! $response->successful()) {
            throw new \Exception('Fish Audio clone error: ' . $response->body());
        }

        $data = $response->json();

        if (empty($data['_id'])) {
            throw new \Exception('Fish Audio did not return a model ID. Response: ' . $response->body());
        }

        return $data['_id'];
    }

    /**
     * Convert text to speech using a specific voice.
     * Returns the raw audio binary (MP3).
     */
    public function textToSpeech(string $voiceId, string $text): string
    {
        $response = Http::withoutVerifying()
            ->timeout(60)
            ->withToken($this->apiKey)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'audio/mpeg',
            ])->post($this->baseUrl . '/v1/tts', [
                'text'         => $text,
                'reference_id' => $voiceId,
                'format'       => 'mp3',
                'latency'      => 'balanced',
                'prosody'      => [
                    'speed'  => 1.0,
                    'volume' => 0,
                ],
            ]);

        if (! $response->successful()) {
            $msg = $response->json()['message'] ?? $response->body();
            if ($response->status() === 402) {
                $msg = "Insufficient balance in your Fish Audio account. Please check your dashboard (https://fish.audio/go-api-key) to see if you have free credits left.";
            }
            throw new \Exception('Fish Audio TTS error: ' . $msg);
        }

        return $response->body();
    }

    /**
     * Delete a voice/model from Fish Audio.
     */
    public function deleteVoice(string $voiceId): void
    {
        Http::withoutVerifying()->withToken($this->apiKey)
            ->delete($this->baseUrl . '/model/' . $voiceId);
    }
}
