<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search and Filters -->
            <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                    <input type="text" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Search exercises...">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Muscle Group</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Muscle Groups</option>
                        <option>Chest</option>
                        <option>Back</option>
                        <option>Shoulders</option>
                        <option>Legs</option>
                        <option>Arms</option>
                        <option>Core</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Exercise Type</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option>Strength</option>
                        <option>Cardio</option>
                        <option>Flexibility</option>
                        <option>Powerlifting</option>
                    </select>
                </div>
            </div>

            <!-- Exercise Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Example Exercise Card 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bench Press</h3>
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Chest
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Strength
                            </span>
                        </div>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                            A compound exercise that targets the chest muscles.
                        </p>
                        
                        <!-- Exercise Details -->
                        <div class="mt-4 border-t pt-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Instructions:</h4>
                            <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                <li>Lie on bench</li>
                                <li>Lower bar to chest</li>
                                <li>Press up</li>
                            </ul>
                        </div>

                        <!-- Tips Section -->
                        <div class="mt-4 border-t pt-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Tips:</h4>
                            <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300">
                                <li>Keep wrists straight</li>
                                <li>Feet flat on ground</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Example Exercise Card 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pull-ups</h3>
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Back
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Strength
                            </span>
                        </div>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                            A bodyweight exercise that targets the back muscles.
                        </p>
                        
                        <div class="mt-4 border-t pt-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Instructions:</h4>
                            <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                <li>Grip the bar with hands shoulder-width apart</li>
                                <li>Pull yourself up until chin over bar</li>
                                <li>Lower with control</li>
                            </ul>
                        </div>

                        <div class="mt-4 border-t pt-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Tips:</h4>
                            <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300">
                                <li>Keep core engaged</li>
                                <li>Avoid swinging</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>