<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

        <div class="max-w-7xl mx-auto p-6">
            <h1 class="text-3xl font-bold mb-6">Welcome, {{ ucfirst(Auth::user()->username) }} 👋</h1>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-gray-500 text-sm uppercase">Average. Monthly Expense</h2>
                    <p class="text-2xl font-bold text-red-500 mt-2">
                        £{{ number_format($expenses ?? 0, 2) }}
                    </p>
                </div>
                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-gray-500 text-sm uppercase">Average Monthly Income</h2>
                    <p class="text-2xl font-bold text-green-500 mt-2">
                        RS {{ number_format($incomes ?? 0, 2) }}
                    </p>
                </div>
                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-gray-500 text-sm uppercase">Most Used Categories</h2>
                    @forelse ($mostUsedCategories as $category)
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-gray-700">{{ $category->name }}</span>
                            <span class="text-sm font-semibold text-blue-500">£{{ number_format($category->expenses_sum_amount, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-gray-400 mt-2">No categories yet.</p>
                    @endforelse
                </div>
            </div>


            <div class="bg-white shadow rounded-2xl p-6 text-center text-gray-400">

            </div>
        </div>
{{--    @endsection--}}
</x-app-layout>
