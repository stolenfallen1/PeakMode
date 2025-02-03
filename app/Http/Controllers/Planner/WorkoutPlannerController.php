<?php

namespace App\Http\Controllers\Planner;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\UserWorkout;
use App\Services\ExerciseApiService;
use Cache;
use Illuminate\Http\Request;

class WorkoutPlannerController extends Controller
{
    //
    protected $exerciseApiService;
    protected const CACHE_TTL = 86400; // 24 hours
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
        $cacheKey = 'muscle_groups';

        return Cache::remember($cacheKey, self::CACHE_TTL, function() use ($muscle) {
            $filters = $muscle ? ['muscle' => $muscle] : [];
            $response = $this->exerciseApiService->getDataFromExercisesAPI($filters);
            $muscleGroups = collect($response)->pluck('muscle')->unique()->values()->toArray();
            \Log::debug('API Response:', $muscleGroups);
            return $muscleGroups;
        });
    }
    
    private function getWorkoutType($type = null) 
    {  
        $cacheKey = 'workout_types';

        return Cache::remember($cacheKey, self::CACHE_TTL, function() use ($type) {
            $filters = $type ? ['type' => $type] : [];
            $response = $this->exerciseApiService->getDataFromExercisesAPI($filters);
            $workoutTypes = collect($response)->pluck('type')->unique()->values()->toArray();
            \Log::debug('API Response:', $workoutTypes);
            return $workoutTypes;
        });
    }

    public function fetchExercises(Request $request) 
    {
        $muscle = $request->get('muscle');
        $type = $request->get('type');

        if (empty($muscle) && empty($type)) {
            return response()->json(['error' => 'Please select a muscle group or workout type'], 400);
        }

        $cacheKey = "exercises_{$muscle}_{$type}";
        $exercises = Cache::remember($cacheKey, 300, function() use ($muscle, $type) {
            return $this->exerciseApiService->getExercisesByMuscleAndType($muscle, $type);
        });

        return response()->json($exercises);
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

    public function saveWorkout(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'exercise' => 'required|string',
            'muscle' => 'required|string',
            'type' => 'required|string',
            'sets' => 'required|integer|min:1',
            'reps' => 'required|integer|min:1'
        ]);

        $cacheKey = "exercises_{$validated['muscle']}_{$validated['type']}";
        $moreInfoOnExercise = collect(Cache::get($cacheKey))
            ->where('name', $validated['exercise'])
            ->first();
    
        $exercise = Exercise::firstOrCreate([
            'name'=> $validated['exercise'],
        ], [
            'muscle_group' => $validated['muscle'],
            'exercise_type' => $validated['type'],
            'equipment' => $moreInfoOnExercise['equipment'] ?? null,
            'difficulty' => $moreInfoOnExercise['difficulty']?? null,
            'instructions' => $moreInfoOnExercise['instructions']?? null,
        ]);
    
        $workout = UserWorkout::create([
            'user_id' => auth()->id(),
            'exercise_id' => $exercise->id,
            'sets' => $validated['sets'],
            'reps' => $validated['reps'],
            'date' => $validated['date'],
            'time' => $validated['time']
        ]);
    
        return response()->json($workout->load('exercise'));
    }
    
    public function getUserWorkouts(Request $request)
    {
        $date = $request->validate(['date' => 'required|date'])['date'];
        
        $workouts = UserWorkout::with('exercise')
            ->where('user_id', auth()->id())
            ->whereDate('date', $date)
            ->get();
    
        return response()->json($workouts);
    }

    public function deleteWorkout($id) 
    {
        $workout = UserWorkout::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $workout->delete();
        return response()->json(['message' => 'Workout deleted successfully']) ;
    }

    public function deleteAllWorkouts(Request $request) 
    {
        $date = $request->validate(['date' => 'required|date'])['date'];
        UserWorkout::where('user_id', auth()->id())
            ->whereDate('date', $date)
            ->delete();

        return response()->json(['message' => 'All workouts deleted successfully']);
    }
}
