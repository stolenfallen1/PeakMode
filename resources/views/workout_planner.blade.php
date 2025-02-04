<x-app-layout>
    <div class="px-3" x-data="{
        showModal: false,
        selectedDay: null,
        isLargeScreen: window.innerWidth >= 768,
        workouts: [],
        currentWorkout: {
            muscle: '',
            type: '',
            exercise: '',
            sets: 1,
            reps: 1,
            time: '',
        },
        exercises: [],

        handleDayClick(day) {
            this.selectedDay = { ...day, workouts: day.workouts || [] };
            if (!this.isLargeScreen) {
                this.showModal = true;
            };

            this.loadDayWorkouts(day.date).then(workouts => {
                this.selectedDay.workouts = workouts;
            });
        },

        async fetchExercises() {
            if (this.currentWorkout.muscle && this.currentWorkout.type) {
                try {
                    const response = await axios.get('/api/fetch_exercises', {
                        params: {
                            muscle: this.currentWorkout.muscle,
                            type: this.currentWorkout.type
                        }
                    });
    
                    this.exercises = response.data.map(exercise => exercise.name);
    
                    if (!this.exercises.includes(this.currentWorkout.exercise)) {
                        this.currentWorkout.exercise = '';
                    }

                } catch(error) {
                    console.error(error);
                    alert('An error occurred while fetching exercises');
                    this.exercises = [];
                }
            } else {
                this.exercises = [];
            }
        },

        initializeScreen() {
            window.addEventListener('resize', () => {
                this.isLargeScreen = window.innerWidth >= 768;
                if (this.isLargeScreen) {
                    this.showModal = false;
                }
            });
        },

        async saveWorkout() {
            if (!this.selectedDay) return;
            try {
                const response = await axios.post('/workout/save', {
                    date: this.selectedDay.date,
                    time: this.currentWorkout.time,
                    exercise: this.currentWorkout.exercise,
                    muscle: this.currentWorkout.muscle,
                    type: this.currentWorkout.type,
                    sets: this.currentWorkout.sets,
                    reps: this.currentWorkout.reps
                });
    
                this.selectedDay.workouts.push(response.data);
    
                this.currentWorkout = {
                    muscle: '',
                    type: '',
                    exercise: '',
                    sets: 0,
                    reps: 0,
                    time: this.currentWorkout.time
                };
            } catch (error) {
                console.error('Error saving workout:', error);
                alert('Failed to save workout');
            }
        },

        async loadDayWorkouts(date) {
            try {
                const response = await axios.get('/workout/user-workouts', {
                    params: { date }
                });
                return response.data;
            } catch (error) {
                console.error('Error loading workouts:', error);
                return [];
            }
        },

        removeWorkout(id) {
            if (!this.selectedDay) return;
            if (!confirm('Are you sure you want to remove this workout?')) return;
            
            axios.delete(`/workout/delete/${id}`)
                .then(() => {
                    this.selectedDay.workouts = this.selectedDay.workouts.filter(workout => workout.id !== id);
                }).catch(error => {
                    console.error('Error deleting workout', error);
                    alert('Failed to delete workout');
                });
        },
        
        removeAllWorkout() {
            if (!this.selectedDay) return;
            if (!confirm('Are you sure you want to remove all workouts?')) return;

            const formattedDate = this.selectedDay.date.split('T')[0];
            
            axios.delete('/workout/delete-all', {
                params: { date: formattedDate }
            }).then(() => {
                this.selectedDay.workouts = [];
            }).catch(error => {
                console.error('Error deleting workouts', error);
                alert('Failed to delete workouts.');
            });
        },

        init() {
            this.$watch('currentWorkout.muscle', () => this.fetchExercises());
            this.$watch('currentWorkout.type', () => this.fetchExercises());
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
            <form @submit.prevent="saveWorkout()" class="bg-white p-6 rounded-xl shadow-lg w-11/12 max-w-lg max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="flex justify-between items-center mb-4 pb-3 border-b">
                    <h2 class="text-xl font-bold" x-text="selectedDay ? selectedDay.dayFullName : 'Selected Day'"></h2>
                    <div class="flex items-center space-x-2">
                        <span>Time: </span>
                        <input
                            x-model="currentWorkout.time"
                            required
                            type="time" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
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
                            <select 
                                id="muscle"
                                required
                                x-model="currentWorkout.muscle"
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
                                required
                                x-model="currentWorkout.type"
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
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Exercise</label>
                            <select 
                                id="exercises" 
                                required
                                x-model="currentWorkout.exercise"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                x-bind:disabled="!currentWorkout.muscle || !currentWorkout.type"
                            >
                                <option value="">Select Exercise</option>
                                <template x-for="exercise in exercises" :key="exercise">
                                    <option x-text="exercise" :value="exercise"></option>
                                </template>
                            </select>
                        </div>

                        <div class="flex gap-3 mb-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700">Sets</label>
                                <input 
                                    type="number" 
                                    min="1"
                                    required
                                    x-model="currentWorkout.sets"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700">Reps</label>
                                <input 
                                    type="number" 
                                    min="1"
                                    required
                                    x-model="currentWorkout.reps"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 mb-4 pb-3 border-b">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            Save Workout
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
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold">Workouts Summary</h2>
                            <button
                                x-on:click="removeAllWorkout()"
                                class="py-1 px-2 bg-red-500 text-white rounded hover:bg-red-600"
                            >
                                Clear All
                            </button>   
                        </div>
                        <div class="space-y-2 max-h-32 overflow-y-auto">
                            <div x-show="selectedDay">
                                <div class="mb-4 flex items-center gap-1">
                                    <p class="text-gray-600" x-text="'Date: ' + selectedDay?.dayFullName + ','" />
                                    <p class="text-gray-600" x-text="selectedDay?.currentMonth" />
                                    <p class="text-gray-600" x-text="selectedDay?.dayNumber + ','"" />
                                    <p class="text-gray-600" x-text="selectedDay?.currentYear" />
                                </div>
                                <template x-if="selectedDay?.workouts?.length">
                                    <ul>
                                        <template x-for="workout in selectedDay.workouts" :key="workout.id">
                                            <li class="relative mb-2 p-3 bg-gray-100 rounded shadow">
                                                <button
                                                    x-on:click="removeWorkout(workout.id)"
                                                    class="absolute top-1 right-1 py-1 px-2 mt-2 mr-4 bg-red-500 text-white rounded hover:bg-red-600"
                                                >
                                                    X
                                                </button>
                                                <div>
                                                    <strong>Muscle Group:</strong> <span x-text="workout.muscle"></span>
                                                    <br>
                                                    <strong>Workout Type:</strong> <span x-text="workout.type"></span>
                                                    <br>
                                                    <strong>Exercise:</strong> <span x-text="workout.exercise"></span>
                                                    <br>
                                                    <strong>Sets:</strong> <span x-text="workout.sets"></span>
                                                    <strong>Reps:</strong> <span x-text="workout.reps"></span>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                                <div x-show="!selectedDay?.workouts?.length" class="text-gray-500">
                                    No workouts scheduled for this day.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- LARGER SCREEN GRID LAYOUT CONTAINER -->
        <div x-show="selectedDay && isLargeScreen" class="hidden md:grid grid-cols-2 gap-6 mt-3">
            <form @submit.prevent="saveWorkout()">
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
                                <input 
                                    x-model="currentWorkout.time"
                                    required
                                    type="time" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
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
                                    required
                                    x-model="currentWorkout.muscle"
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
                                    required
                                    x-model="currentWorkout.type"
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
                                <select 
                                    id="exercises" 
                                    required
                                    x-model="currentWorkout.exercise"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    x-bind:disabled="!currentWorkout.muscle || !currentWorkout.type"
                                >
                                    <option value="">Select Exercise</option>
                                    <template x-for="exercise in exercises" :key="exercise">
                                        <option x-text="exercise" :value="exercise"></option>
                                    </template>
                                </select>
                            </div>
            
                            <div class="mb-2 flex items-center space-x-4">
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium text-gray-700">Sets</label>
                                    <input 
                                        type="number" 
                                        min="1"
                                        required
                                        x-model="currentWorkout.sets"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium text-gray-700">Repetition</label>
                                    <input 
                                        type="number" 
                                        min="1"
                                        required
                                        x-model="currentWorkout.reps"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
            
                            <button type="submit" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Save Workout
                            </button>
                        </div>
                    </section>
                </div>
            </form>
            
            <!-- Right Side: Workouts Summary Section -->
            <section class="bg-white p-6 rounded shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Workouts Summary</h2>
                    <button
                        x-on:click="removeAllWorkout()"
                        class="py-1 px-2 bg-red-500 text-white rounded hover:bg-red-600"
                    >
                        Clear All
                    </button>   
                </div>
                <div x-show="selectedDay">
                    <div class="mb-4">
                        <h3 class="font-semibold text-lg" x-text="selectedDay?.dayFullName"></h3>
                        <p class="text-gray-600" x-text="'Date: ' + selectedDay?.date"></p>
                    </div>
                    <template x-if="selectedDay?.workouts?.length">
                        <ul>
                            <template x-for="workout in selectedDay.workouts" :key="workout.id">
                                <li class="relative mb-2 p-3 bg-gray-100 rounded shadow">
                                    <button
                                        x-on:click="removeWorkout(workout.id)"
                                        class="absolute top-1 right-1 py-1 px-2 mt-2 mr-4 bg-red-500 text-white rounded hover:bg-red-600"
                                    >
                                        X
                                    </button>
                                    <div>
                                        <strong>Muscle Group:</strong> <span x-text="workout.exercise.muscle_group"></span>
                                        <br>
                                        <strong>Workout Type:</strong> <span x-text="workout.exercise.exercise_type"></span>
                                        <br>
                                        <strong>Exercise:</strong> <span x-text="workout.exercise.name"></span>
                                        <br>
                                        <strong>Sets:</strong> <span x-text="workout.sets"></span>
                                        <strong>Reps:</strong> <span x-text="workout.reps"></span>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </template>
                    <div x-show="!selectedDay?.workouts?.length" class="text-gray-500">
                        No workouts scheduled for this day.
                    </div>
                </div>
            </section>            
        </div>
    </div>

</x-app-layout>