<?php

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

Route::get('/workout_exercises', function () {
    return view('workout_exercises');
})->middleware(['auth', 'verified'])->name('workout_exercises');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
