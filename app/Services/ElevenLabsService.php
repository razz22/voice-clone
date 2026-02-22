<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class ElevenLabsService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.elevenlabs.io/v1';

    public function __construct()
    {
        $this->apiKey = config('services.elevenlabs.api_key') ?? '';
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Clone a voice by uploading an audio file.
     * Returns the new voice_id from ElevenLabs.
     */
    public function cloneVoice(string $name, string $filePath, string $description = ''): string
    {
        $response = Http::withoutVerifying()->withHeaders([
            'xi-api-key' => $this->apiKey,
        ])->attach(
            'files',
            file_get_contents($filePath),
            basename($filePath)
        )->post($this->baseUrl . '/voices/add', [
            'name'        => $name,
            'description' => $description,
        ]);

        if (! $response->successful()) {
            throw new \Exception('ElevenLabs clone error: ' . $response->body());
        }

        $data = $response->json();

        if (empty($data['voice_id'])) {
            throw new \Exception('ElevenLabs did not return a voice_id. Response: ' . $response->body());
        }

        return $data['voice_id'];
    }

    /**
     * Convert text to speech using a specific voice.
     * Returns the raw audio binary (MP3).
     */
    public function textToSpeech(string $voiceId, string $text, array $options = [], string $modelId = 'eleven_multilingual_v2'): string
    {
        $response = Http::withoutVerifying()->withHeaders([
            'xi-api-key'   => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/text-to-speech/' . $voiceId, [
            'text'     => $text,
            'model_id' => $modelId,
            'voice_settings' => [
                'stability'         => $options['stability'] ?? 0.5,
                'similarity_boost'  => $options['similarity'] ?? 0.75,
                'style'             => $options['style'] ?? 0.0,
                'use_speaker_boost' => $options['use_speaker_boost'] ?? true,
            ],
        ]);

        if (! $response->successful()) {
            throw new \Exception('ElevenLabs TTS error: ' . $response->body());
        }

        return $response->body();
    }

    /**
     * Delete a voice from ElevenLabs.
     */
    public function deleteVoice(string $voiceId): void
    {
        Http::withoutVerifying()->withHeaders([
            'xi-api-key' => $this->apiKey,
        ])->delete($this->baseUrl . '/voices/' . $voiceId);
    }
}
