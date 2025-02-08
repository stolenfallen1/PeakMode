<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">Total Workouts</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalWorkouts }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">This Week's Workouts</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $weeklyWorkouts }}</div>
                    <div class="text-blue-600 text-xs mt-1">
                        {{ 7 - \Carbon\Carbon::now()->dayOfWeek }} days remaining
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">Most Trained</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $mostTrainedMuscle ? ucfirst($mostTrainedMuscle->muscle_group) : 'N/A' }}
                    </div>
                    <div class="text-blue-600 text-xs mt-1">
                        {{ $mostTrainedMuscle ? $mostTrainedMuscle->count . ' workouts' : 'No workouts yet' }}
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">Weekly Progress</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ min(round(($weeklyBreakdown->count() / 4) * 100), 100) }}%
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                        <div class="bg-blue-600 h-2.5 rounded-full" 
                             style="width: {{ min(($weeklyBreakdown->count() / 4) * 100, 100) }}%">
                        </div>
                    </div>
                    <div class="text-blue-600 text-xs mt-1">
                        Target: 4 active days/week
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Activity -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recent Activity</h2>
                        <div class="space-y-4">
                            @forelse($recentWorkouts as $workout)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $workout->exercise->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $workout->exercise->muscle_group }} | 
                                            {{ $workout->sets }} sets × {{ $workout->reps }} reps
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($workout->workout_schedule->date)->format('M d, Y') }}
                                            at {{ \Carbon\Carbon::parse($workout->workout_schedule->time)->format('g:i A') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No recent workouts</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Muscle Group Distribution -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Muscle Group Focus</h2>
                        <div class="space-y-4">
                            @forelse($muscleGroupStats as $stat)
                                <div class="relative">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ ucfirst($stat->muscle_group) }}
                                        </span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $stat->count }}
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" 
                                             style="width: {{ ($stat->count / $totalWorkouts) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No workout data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Breakdown -->
            <div class="mt-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Weekly Activity</h2>
                        <div class="grid grid-cols-7 gap-4">
                            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                @php
                                    $dayDate = \Carbon\Carbon::now()->startOfWeek()->addDays($loop->index)->format('Y-m-d');
                                    $workoutCount = $weeklyBreakdown->firstWhere('workout_date', $dayDate)?->workout_count ?? 0;
                                @endphp
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $day }}</div>
                                    <div class="mt-2 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                        <span class="text-xl font-bold {{ $workoutCount > 0 ? 'text-blue-600' : 'text-gray-400' }}">
                                            {{ $workoutCount }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Route to other features -->
            <div class="mt-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
                        <div class="flex flex-col md:flex-row gap-4">
                            <a href="{{ route('workout_planner') }}" 
                                class="w-full flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span class="text-gray-900 dark:text-white">Plan Workout</span>
                            </a>
                            <a href="{{ route('workout_exercises') }}" 
                                class="w-full flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <span class="text-gray-900 dark:text-white">View Exercises</span>
                            </a>
                            <a href="{{ route('find_gyms') }}" 
                                class="w-full flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-gray-900 dark:text-white">Find Nearest Gym</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>