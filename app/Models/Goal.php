<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'university_name',
        'study_program',
        'target_score',
        'photo_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get photo URL or default placeholder illustration.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }
        return 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=1200&q=80';
    }
}
