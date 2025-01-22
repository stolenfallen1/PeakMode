<x-app-layout>
    <div class="px-3" x-data="{
        showModal: false,
        selectedDay: null,
        isLargeScreen: window.innerWidth >= 768,

        initializeScreen() {
            window.addEventListener('resize', () => {
                this.isLargeScreen = window.innerWidth >= 768;
                if (this.isLargeScreen) {
                    this.showModal = false;
                }
            });
        },

        handleDayClick(day) {
            this.selectedDay = day;
            if (!this.isLargeScreen) {
                this.showModal = true;
            }
        }
    }" x-init="initializeScreen()">
        <!-- CALENDAR GRID -->
        <div class="grid md:grid-cols-7 gap-2 cursor-pointer mt-3">
            @foreach ($daysOfWeek as $day)
                <div
                    class="day-card {{ $day['isToday'] ? 'bg-blue-100 border-b-2 border-red-400' : 'bg-neutral-200' }} px-3 py-4 rounded hover:bg-blue-200"
                    x-bind:class="{
                        'ring-2 ring-blue-500 bg-blue-200': selectedDay && selectedDay.dayNumber === '{{ $day['dayNumber'] }}'
                        && selectedDay?.dayShortName === '{{ $day['dayShortName'] }}'
                    }"
                    x-on:click="handleDayClick({{ json_encode($day) }})"
                >
                    <div class="text-lg font-bold">{{ $day['dayShortName'] }}</div>
                    <div class="flex items-center space-x-1">
                        <span class="text-sm">{{ $day['currentShortMonth'] }}</span>
                        <span class="text-sm">{{ $day['dayNumber'] }},</span>
                        <span class="text-sm">{{ $day['currentYear'] }}</span>
                    </div>
                    @if (isset($day['workouts']))
                        @foreach ($day['workouts'] as $workout)
                        <div class="workout-badge p-2 bg-green-100 rounded mt-2">
                            {{ $workout->type }}
                        </div>                    
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>
        <!-- SMALL SCREENS MODAL -->
        <div
            x-show="showModal && !isLargeScreen"
            x-cloak 
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-999"
            x-on:click.away="showModal = false"
        >
            <div class="bg-white p-6 rounded-xl shadow-lg w-11/12 max-w-lg max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="flex justify-between items-center mb-4 pb-3 border-b">
                    <h2 class="text-xl font-bold" x-text="selectedDay ? selectedDay.dayFullName : 'Selected Day'"></h2>
                    <div class="flex items-center space-x-2">
                        <span>Time: </span>
                        <input type="time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <button
                            class="p-2 hover:bg-gray-100 rounded-full"
                            x-on:click="showModal = false"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Form Content -->
                <div>
                    <!-- Muscle Group, Workout Type & Time -->
                    <div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Muscle Group</label>
                            <form method="GET" action="{{ route('workout_planner') }}">
                                <select 
                                    name="muscle"
                                    onchange="this.form.submit()"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select a muscle group</option>
                                    @if (!empty($muscleGroups))                                        
                                        @foreach ($muscleGroups as $muscleGroup)
                                            <option value="{{ $muscleGroup }}" {{ request('muscle') == $muscleGroup ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $muscleGroup)) }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option disabled>No muscle groups available</option>
                                    @endif
                                </select>
                            </form>                        
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Workout Type</label>
                            <form method="GET" action="{{ route('workout_planner') }}">
                                <select 
                                    name="exercise_type"
                                    onchange="this.form.submit()"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select a muscle group</option>
                                    @if (!empty($exerciseTypes))                                        
                                        @foreach ($exerciseTypes as $exerciseType)
                                            <option value="{{ $exerciseType }}" {{ request('type') == $exerciseType ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $exerciseType)) }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option disabled>No workout types available</option>
                                    @endif
                                </select>
                            </form>                          
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Exercise</label>
                            <select class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option>Test Exercise 1</option>
                                <option>Test Exercise 2</option>
                                <option>Test Exercise 3</option>
                                <option>Test Exercise 4</option>
                            </select>
                        </div>

                        <div class="flex gap-3 mb-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700">Sets</label>
                                <input type="number" min="1" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700">Reps</label>
                                <input type="number" min="1" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 mb-4 pb-3 border-b">
                        <button class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            Save Exercise
                        </button>
                        <button 
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                            x-on:click="showModal = false"
                        >
                            Cancel
                        </button>
                    </div>

                    <!-- Workout Summary Section -->
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Workout Summary</h3>
                        <div class="space-y-2 max-h-32 overflow-y-auto">
                            <div x-show="selectedDay">
                                <div class="mb-4 flex items-center gap-1">
                                    <p class="text-gray-600" x-text="'Date: ' + selectedDay?.dayFullName + ','" />
                                    <p class="text-gray-600" x-text="selectedDay?.currentMonth" />
                                    <p class="text-gray-600" x-text="selectedDay?.dayNumber + ','"" />
                                    <p class="text-gray-600" x-text="selectedDay?.currentYear" />
                                </div>
                                <template x-if="selectedDay?.workouts?.length">
                                    <div class="space-y-2">
                                        <template x-for="workout in selectedDay.workouts" :key="workout.id">
                                            <div class="p-3 bg-green-50 rounded">
                                                <div x-text="workout.type" class="font-medium"></div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <div x-show="!selectedDay?.workouts?.length" class="text-gray-500">
                                    No workouts scheduled for this day
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- LARGER SCREEN GRID LAYOUT CONTAINER -->
        <div x-show="selectedDay && isLargeScreen" class="hidden md:grid grid-cols-2 gap-6 mt-3">
            <div class="space-y-3">
                <!-- Left Side: Schedule and Workout Section -->
                <!-- Schedule Section -->
                <section class="bg-white p-6 rounded shadow-lg">
                    <aside class="flex items-center justify-between">
                        <h2 class="text-xl font-bold mb-2">Schedule for 
                            <span x-text="selectedDay ? selectedDay.dayFullName : 'Selected Day'"></span>
                        </h2>
                        <div class="flex items-center space-x-2">
                            <span>Time: </span>
                            <input type="time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </aside>
                </section>
        
                <!-- Workout Section -->
                <section class="bg-white p-6 rounded shadow-lg">
                    <h2 class="text-xl font-bold mb-2">Workout Details</h2>
                    <div x-show="selectedDay" class="mt-3">
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Muscle Group</label>
                            <select 
                                id="muscle"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select a muscle group</option>
                                @if (!empty($muscleGroups))                                        
                                    @foreach ($muscleGroups as $muscleGroup)
                                        <option value="{{ $muscleGroup }}" {{ request('muscle') == $muscleGroup ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $muscleGroup)) }}
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled>No muscle groups available</option>
                                @endif
                            </select>
                        </div>
                        
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Workout Type</label>
                            <select 
                                id="type"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select a workout type</option>
                                @if (!empty($exerciseTypes))                                        
                                    @foreach ($exerciseTypes as $exerciseType)
                                        <option value="{{ $exerciseType }}" {{ request('type') == $exerciseType ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $exerciseType)) }}
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled>No workout types available</option>
                                @endif
                            </select>
                        </div>     
        
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Exercise</label>
                            <select id="exercises" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" disabled>
                                <option value="">Select Exercise</option>
                            </select>
                        </div>
        
                        <div class="mb-2 flex items-center space-x-4">
                            <div class="w-1/2">
                                <label class="block text-sm font-medium text-gray-700">Sets</label>
                                <input type="number" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-sm font-medium text-gray-700">Repetition</label>
                                <input type="number" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
        
                        <button class="mt-3 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Save Workout
                        </button>
                    </div>
                </section>
            </div>
        
            <!-- Right Side: Workouts Summary Section -->
            <section class="bg-white p-6 rounded shadow-lg">
                <h2 class="text-xl font-bold mb-4">Workouts Summary</h2>
                <div x-show="selectedDay">
                    <div class="mb-4">
                        <h3 class="font-semibold text-lg" x-text="selectedDay?.dayFullName"></h3>
                        <p class="text-gray-600" x-text="'Date: ' + selectedDay?.date"></p>
                    </div>
                    <template x-if="selectedDay?.workouts?.length">
                        <div class="space-y-2">
                            <template x-for="workout in selectedDay.workouts" :key="workout.id">
                                <div class="p-3 bg-green-50 rounded">
                                    <div x-text="workout.type" class="font-medium"></div>
                                </div>
                            </template>
                        </div>
                    </template>
                    <div x-show="!selectedDay?.workouts?.length" class="text-gray-500">
                        No workouts scheduled for this day
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="{{ asset('js/functions/workout_planner.js') }}"></script>

</x-app-layout>