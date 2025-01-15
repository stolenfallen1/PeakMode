<?php

namespace App\Http\Controllers\Planner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkoutPlannerController extends Controller
{
    //
    public function weeklySchedule() 
    {
        $currentDate = now();
        $weekStart = $currentDate->startOfWeek();
        
        $daysOfWeek = collect(range(0, 6))->map(function($day) use ($weekStart) {
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

        return view('workout_planner', compact('daysOfWeek'));
    }
}
