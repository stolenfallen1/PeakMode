<x-app-layout>
    <div class="py-6" x-data="{
        location: '',
        gyms: [],
        isLoading: false,
        hasSearched: false,

        init() {
            this.isLoading = false;
            this.gyms = [];
            this.hasSearched = false;
        },

        async searchGyms() {
            this.isLoading = true;
            this.hasSearched = true;
            try {
                const response = await axios.post('{{ route("find_gyms.search") }}', {
                    location: this.location,
                });

                this.gyms = response.data.places || [];
            } catch (error) {
                console.error('Error:', error);
                this.gyms = [];
            } finally {
                this.isLoading = false;
            }
        }
    }" x-init="init()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search Section -->
            <div class="mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Find Nearby Gyms</h2>
                    <form @submit.prevent="searchGyms()">
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <input type="text" 
                                    required
                                    x-model="location"
                                    placeholder="Enter location" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <button type="submit" 
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                                x-bind:disabled="isLoading">
                                <span x-show="!isLoading">Search</span>
                                <span x-show="isLoading">Searching...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Grid -->
            <div x-show="!isLoading && gyms.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="gym in gyms" :key="gym.position"> <!-- Position is ID -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="gym.title"></h3>
                                <template x-if="gym.rating">
                                    <div class="flex items-center bg-blue-50 dark:bg-blue-900 rounded-full px-2 py-1">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="ml-1 text-sm font-medium" x-text="gym.rating"></span>
                                        <span class="ml-1 text-xs text-gray-600 dark:text-gray-400" x-text="`(${gym.ratingCount})`"></span>
                                    </div>
                                </template>
                            </div>
                            
                            <div class="mt-3 space-y-2">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span x-text="gym.address"></span>
                                </div>
                                
                                <template x-if="gym.phoneNumber">
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <span x-text="gym.phoneNumber"></span>
                                    </div>
                                </template>

                                <template x-if="gym.category">
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        <span x-text="gym.category"></span>
                                    </div>
                                </template>
                            </div>

                            <template x-if="gym.website">
                                <div class="mt-4">
                                    <a :href="gym.website" 
                                        target="_blank" 
                                        class="inline-flex items-center text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                                        <span>Visit Website</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- No Results State -->
            <div x-show="!isLoading && hasSearched && gyms.length === 0" class="text-center py-12">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No gyms found nearby.</h3>
            </div>

            <!-- Loading State -->
            <div x-show="isLoading" class="text-center py-12">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Searching for gyms...</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Please wait.</p>
            </div>
        </div>
    </div>
</x-app-layout>