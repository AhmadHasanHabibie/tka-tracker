<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TKASubject extends Model
{
    use HasFactory;

    protected $table = 'tka_subjects';

    protected $fillable = [
        'name',
        'code',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scores(): HasMany
    {
        return $this->hasMany(TKASubjectScore::class, 'tka_subject_id');
    }
}
