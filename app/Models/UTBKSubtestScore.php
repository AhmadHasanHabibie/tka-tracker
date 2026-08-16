<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UTBKSubtestScore extends Model
{
    use HasFactory;

    protected $table = 'utbk_subtest_scores';

    protected $fillable = [
        'utbk_tryout_id',
        'subtest',
        'score',
    ];

    protected $casts = [
        'score' => 'float',
    ];

    public function utbkTryout(): BelongsTo
    {
        return $this->belongsTo(UTBKTryout::class, 'utbk_tryout_id');
    }
}
