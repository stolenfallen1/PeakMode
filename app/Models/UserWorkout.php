<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserWorkout extends Model
{
    protected $fillable = [
        'user_id',
        'exercise_id',
        'schedule_id',
        'sets',
        'reps',
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

    public function workout_schedule(): BelongsTo 
    {
        return $this->belongsTo(WorkoutSchedule::class, 'schedule_id');
    }
}
