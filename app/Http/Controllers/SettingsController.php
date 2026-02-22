<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'elevenlabs_api_key' => 'nullable|string|max:255',
            'fish_audio_api_key' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'elevenlabs_api_key' => $request->elevenlabs_api_key,
            'fish_audio_api_key' => $request->fish_audio_api_key,
        ]);

        return redirect()->route('settings.index')->with('success', 'API settings updated successfully!');
    }
}
