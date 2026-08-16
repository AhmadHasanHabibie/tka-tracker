<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TodoTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'materi_id',
        'title',
        'status',
        'due_date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'due_date' => 'date',
    ];

    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class);
    }
}
