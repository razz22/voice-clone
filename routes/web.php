<?php
 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoiceController;
use App\Http\Controllers\SpeechController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('speech.index');
});

Route::redirect('/dashboard', '/speak');

Route::get('/speak', [SpeechController::class, 'index'])->name('speech.index');
Route::post('/speak/generate', [SpeechController::class, 'generate'])->name('speech.generate');

Route::middleware(['auth'])->group(function () {
    Route::get('/voices', [VoiceController::class, 'index'])->name('voices.index');
    Route::post('/voices', [VoiceController::class, 'store'])->name('voices.store');
    Route::delete('/voices/{voice}', [VoiceController::class, 'destroy'])->name('voices.destroy');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
