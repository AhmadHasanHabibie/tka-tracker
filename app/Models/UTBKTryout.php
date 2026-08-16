<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UTBKTryout extends Model
{
    use HasFactory;

    protected $table = 'utbk_tryouts';

    protected $fillable = [
        'user_id',
        'name',
        'date',
        'overall_score',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'date' => 'date',
        'overall_score' => 'float',
    ];

    public function subtestScores(): HasMany
    {
        return $this->hasMany(UTBKSubtestScore::class, 'utbk_tryout_id');
    }
}
