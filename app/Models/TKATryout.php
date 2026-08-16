<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TKATryout extends Model
{
    use HasFactory;

    protected $table = 'tka_tryouts';

    protected $fillable = [
        'user_id',
        'name',
        'date',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'date' => 'date',
    ];

    public function subjectScores(): HasMany
    {
        return $this->hasMany(TKASubjectScore::class, 'tka_tryout_id');
    }
}
