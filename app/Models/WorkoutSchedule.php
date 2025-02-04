<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutSchedule extends Model
{
    //
    protected $fillable = [
        'user_id',
        'date',
        'time'
    ];

    protected $casts = [
        'date'=> 'date',
        'time' => 'datetime:H:i:'
    ];

    public function user_workout(): HasMany
    {
        return $this->hasMany(UserWorkout::class);
    }
}
