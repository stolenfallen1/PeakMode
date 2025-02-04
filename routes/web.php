<?php

use App\Http\Controllers\FindGym\FindGymController;
use App\Http\Controllers\Planner\WorkoutPlannerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/workout_planner', [WorkoutPlannerController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('workout_planner');

Route::get('/api/fetch_exercises', [WorkoutPlannerController::class, 'fetchExercises'])
    ->middleware(['auth', 'verified'])
    ->name('api.fetch_exercises');


Route::get('/workout_exercises', function () {
    return view('workout_exercises');
})->middleware(['auth', 'verified'])->name('workout_exercises');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Workout planner
    // Save workout
    Route::post('/workout/save', [WorkoutPlannerController::class, 'saveWorkout'])->name('workout.save');
    // Get user workouts for the current day
    Route::get('/workout/user-workouts', [WorkoutPlannerController::class, 'getUserWorkouts'])->name('workout.user-workouts');
    // Delete specific workout
    Route::delete('/workout/delete/{id}', [WorkoutPlannerController::class, 'deleteWorkout'])->name('workout.delete');
    // Delete all workouts for the current day
    Route::delete('/workout/delete-all', [WorkoutPlannerController::class,'deleteAllWorkouts'])->name('workout.delete.all');

    // Find GYMS
    // Go to find gyms page
    Route::get('/find_gyms', [FindGymController::class, 'index'])->name('find_gyms');
    // Search gyms
    Route::post('/find_gyms/search', [FindGymController::class, 'searchGym'])->name('find_gyms.search');
});

require __DIR__.'/auth.php';
