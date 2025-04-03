
<x-app-layout>
    <div class="container mx-auto px-4 py-12 min-h-screen bg-gradient-to-br from-gray-100 to-indigo-50 dark:from-gray-900 dark:to-indigo-950">
        <div class="max-w-6xl mx-auto">

            <header class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-gray-800 dark:text-gray-100 tracking-tight">
                     Overview
                </h1>

            </header>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">

                <div class="relative bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-t-xl"></div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Users</h3>
                    <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mt-3">{{ $userNumber }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Active accounts</p>
                </div>
                <div class="relative bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-t-xl"></div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Categories</h3>
                    <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mt-3">{{ $categoryNumber }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Content segments</p>
                </div>
                <div class="relative bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-t-xl"></div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Monthly Avg. Income</h3>
                    <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mt-3">${{ number_format($averageIncome, 2) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">This month’s earnings</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Most Used Categories</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($mostUsedCategories as $mostUsedCategory)
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900 transition duration-200">
                            <span class="w-3 h-3 bg-indigo-500 rounded-full mr-3"></span>
                            <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $mostUsedCategory->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>


        </div>
    </div>




</x-app-layout>
