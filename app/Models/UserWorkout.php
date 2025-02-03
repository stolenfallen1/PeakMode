<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserWorkout extends Model
{
    protected $fillable = [
        'user_id',
        'exercise_id',
        'sets',
        'reps',
        'date',
        'time'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i:'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
