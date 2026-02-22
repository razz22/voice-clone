<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'elevenlabs_voice_id',
        'provider',
        'sample_path',
        'status',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
