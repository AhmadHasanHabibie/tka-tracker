<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TKASubjectScore extends Model
{
    use HasFactory;

    protected $table = 'tka_subject_scores';

    protected $fillable = [
        'tka_tryout_id',
        'tka_subject_id',
        'subject_name',
        'subject_type',
        'score',
    ];

    protected $casts = [
        'score' => 'float',
    ];

    public function tkaTryout(): BelongsTo
    {
        return $this->belongsTo(TKATryout::class, 'tka_tryout_id');
    }

    public function tkaSubject(): BelongsTo
    {
        return $this->belongsTo(TKASubject::class, 'tka_subject_id');
    }
}
