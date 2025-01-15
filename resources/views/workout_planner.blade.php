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
        <div class="grid grid-cols-7 gap-2 cursor-pointer mt-3">
            @foreach ($daysOfWeek as $day)
                <div
                    class="day-card {{ $day['isToday'] ? 'bg-blue-100 border-b-2 border-red-400' : 'bg-neutral-200' }} p-4 rounded hover:bg-blue-200"
                    x-bind:class="{
                        'ring-2 ring-blue-500 bg-blue-200': selectedDay && selectedDay.dayNumber === '{{ $day['dayNumber'] }}'
                        && selectedDay?.dayShortName === '{{ $day['dayShortName'] }}'
                    }"
                    x-on:click="handleDayClick({{ json_encode($day) }})"
                >
                    <div class="text-sm font-bold">{{ $day['dayShortName'] }}</div>
                    <div class="text-lg">{{ $day['dayNumber'] }}</div>
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
            <div class="bg-white p-6 rounded shadow-lg w-96" x-on:click.stop>
                <h2 class="text-xl font-bold mb-4" x-text="selectedDay?.dayFullName"></h2>
                <div class="text-gray-600" x-text="'Day Number: ' + selectedDay?.dayNumber"></div>
                <template x-if="selectedDay?.workouts">
                    <div class="mt-3">
                        <h3 class="text-lg font-semibold">Workouts:</h3>
                        <ul>
                            <template x-for="workout in selectedDay.workouts" :key="workout.id">
                                <li class="p-2 bg-green-100 rounded mt-2" x-text="workout.type"></li>
                            </template>
                        </ul>
                    </div>
                </template>
                <button
                    class="mt-6 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                    x-on:click="showModal = false"
                >
                    Close
                </button>
            </div>
        </div>
        <!-- LARGER SCREEN GRID LAYOUT CONTAINER -->
        <div x-show="selectedDay && isLargeScreen" class="hidden md:grid grid-cols-2 gap-6 mt-3">
            <div class="space-y-3">

                <!-- Schedule Section -->
                <section class="bg-white p-6 rounded shadow-lg">
                    <h2 class="text-xl font-bold mb-2">Schedule for 
                        <span x-text="selectedDay ? selectedDay.dayFullName : 'Selected Day'"></span>
                    </h2>
                    <div x-show="selectedDay" class="mt-3">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Workout Type</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option>Strength Training</option>
                                <option>Cardio</option>
                                <option>HIIT</option>
                                <option>Yoga</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Time</label>
                            <input type="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </section>

                <!-- Workout Section -->
                <section class="bg-white p-6 rounded shadow-lg">
                    <h2 class="text-xl font-bold mb-2">Workout Details</h2>
                    <div x-show="selectedDay" class="mt-3">
                        <textarea 
                            class="w-full h-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Enter workout details..."
                        ></textarea>
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

</x-app-layout>