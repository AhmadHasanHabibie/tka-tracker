<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'title',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function todoTasks(): HasMany
    {
        return $this->hasMany(TodoTask::class);
    }

    /**
     * Get completion percentage for todos inside this materi.
     */
    public function getProgressPercentAttribute(): int
    {
        $total = $this->todoTasks->count();
        if ($total === 0) {
            return 0;
        }
        $completed = $this->todoTasks->where('status', 'completed')->count();
        return (int) round(($completed / $total) * 100);
    }
}
