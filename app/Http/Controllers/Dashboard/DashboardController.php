<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserWorkout;
use App\Models\Exercise;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() 
    {
        $user = auth()->user();
        $now = Carbon::now();

        $totalWorkouts = UserWorkout::where('user_id', $user->id)->count();

        $weeklyWorkouts = UserWorkout::where('user_id', $user->id)
            ->whereHas('workout_schedule', function ($query) use ($now) {
                $query->whereBetween('date', [
                    $now->startOfWeek()->format('Y-m-d'),
                    $now->endOfWeek()->format('Y-m-d'),
                ]);
            })->count();

        $mostTrainedMuscle = Exercise::select('muscle_group', DB::raw('count(*) as count'))
            ->join('user_workouts', 'exercises.id', '=', 'user_workouts.exercise_id')
            ->where('user_workouts.user_id', $user->id)
            ->groupBy('muscle_group')
            ->orderByDesc('count')
            ->first();

        $recentWorkouts = UserWorkout::with(['exercise', 'workout_schedule'])
            ->where('user_id', $user->id)
            ->whereHas('workout_schedule', function ($query) {
                $query->orderBy('date', 'desc')
                    ->orderBy('time', 'desc');
            })->limit(5)->get();

        $weeklyBreakdown = UserWorkout::select(
                DB::raw('DISTINCT DATE(workout_schedules.date) as workout_date'),
                DB::raw('count(*) as workout_count')
            )
            ->join('workout_schedules', 'user_workouts.schedule_id', '=', 'workout_schedules.id')
            ->where('user_workouts.user_id', $user->id)
            ->whereBetween('workout_schedules.date', [
                $now->startOfWeek()->format('Y-m-d'),
                $now->endOfWeek()->format('Y-m-d'),
            ])->groupBy('workout_date')->get();

        $muscleGroupStats = Exercise::select('muscle_group', DB::raw('count(*) as count'))
            ->join('user_workouts', 'exercises.id', '=', 'user_workouts.exercise_id')
            ->where('user_workouts.user_id', $user->id)
            ->groupBy('muscle_group')
            ->get();

        return view('dashboard', compact(
            'totalWorkouts',
            'weeklyWorkouts',
            'mostTrainedMuscle',
            'recentWorkouts',
            'weeklyBreakdown',
            'muscleGroupStats'
        ));
    }

    public function updateWorkoutTarget(Request $request)
    {
        $validated = $request->validate([
            'target' => 'required|integer|min:1|max:7'
        ]);

        auth()->user()->update([
            'weekly_workout_target' => $validated['target']
        ]);

        return redirect()->back();
    }
}
