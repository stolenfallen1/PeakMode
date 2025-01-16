<?php

namespace App\Http\Controllers\Planner;

use App\Http\Controllers\Controller;
use App\Services\ExerciseApiService;
use Illuminate\Http\Request;

class WorkoutPlannerController extends Controller
{
    //
    protected $exerciseApiService;
    public function __construct(ExerciseApiService $exerciseApiService) 
    {
        $this->exerciseApiService = $exerciseApiService;
    } 

    private function getDaysOfWeek() 
    {
        $currentDate = now();
        $weekStart = $currentDate->startOfWeek();
        
        return collect(range(0, 6))->map(function($day) use ($weekStart) {
            $date = $weekStart->copy()->addDays($day);
            return [
                'date'              => $date,
                'dayShortName'      => $date->format('D'),
                'dayFullName'       => $date->format('l'),
                'currentMonth'      => $date->format('F'),
                'currentShortMonth' => $date->format('M'),
                'dayNumber'         => $date->format('d'),
                'currentYear'       => $date->format('Y'),
                'isToday'           => $date->isToday(),
                'workouts'          => [] 
            ];
        });
    }

    private function getMuscleGroup($muscle = null) 
    {
        $filters = $muscle ? ['muscle' => $muscle] : [];
        $response = $this->exerciseApiService->getExercises($filters);
        $muscleGroups = collect($response)->pluck('muscle')->unique()->values()->toArray();
        \Log::debug('API Response:', $muscleGroups);
        return $muscleGroups;
    }
    
    private function getWorkoutType($type = null) 
    {  
        $filters = $type ? ['type' => $type] : [];
        $response = $this->exerciseApiService->getExercises($filters);
        $exerciseTypes = collect($response)->pluck('type')->unique()->values()->toArray();
        \Log::debug('API Response:', $exerciseTypes);
        return $exerciseTypes;
    }

    public function index(Request $request) 
    {
        $type = $request->get('type');
        $muscle = $request->get('muscle');
        $daysOfWeek = $this->getDaysOfWeek();
        $muscleGroups = $this->getMuscleGroup($muscle);
        $exerciseTypes = $this->getWorkoutType($type);

        return view('workout_planner', compact('daysOfWeek', 'muscleGroups', 'exerciseTypes'));
    }
}
