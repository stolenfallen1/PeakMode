<x-app-layout>
    <div class="py-6" x-data="{
        location: '',
        radius: '5',
        gyms: [],
        isLoading: false,

        init() {
            this.isLoading = false;
            this.gyms = [];
        },

        async searchGyms() {
            this.isLoading = true;
            try {
                const response = await axios.post('{{ route("find_gyms.search") }}', {
                    location: this.location,
                    radius: this.radius
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
                                    x-model="location"
                                    placeholder="Enter location or zip code..." 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <select x-model="radius" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="5">Within 5 km</option>
                                    <option value="10">Within 10 km</option>
                                    <option value="20">Within 20 km</option>
                                    <option value="50">Within 50 km</option>
                                </select>
                            </div>
                            <button type="submit" 
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                                :disabled="isLoading">
                                <span x-show="!isLoading">Search</span>
                                <span x-show="isLoading">Searching...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Grid -->
            <div x-show="!isLoading && gyms.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="gym in gyms" :key="gym.id">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="gym.title"></h3>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span x-text="gym.address"></span>
                            </div>
                            <template x-if="gym.rating">
                                <div class="mt-4 flex items-center">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="ml-1 text-sm text-gray-500" x-text="`${gym.rating} (${gym.reviewsCount} reviews)`"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- No Results State -->
            <div x-show="!isLoading && gyms.length === 0" class="text-center py-12">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No gyms found</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Try adjusting your search criteria</p>
            </div>

            <!-- Loading State -->
            <div x-show="isLoading" class="text-center py-12">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Searching for gyms...</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Please wait</p>
            </div>
        </div>
    </div>
</x-app-layout>