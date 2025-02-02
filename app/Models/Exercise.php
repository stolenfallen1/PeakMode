<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'description',
        'muscle_group',
        'exercise_type',
        'equipment',
        'difficulty',
        'instructions'
    ];

    protected $casts = [
        'instructions' => 'array'  
    ];

    public function userWorkouts(): HasMany
    {
        return $this->hasMany(UserWorkout::class);
    }
}
