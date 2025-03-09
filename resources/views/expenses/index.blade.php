<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Expenses') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                        <div class="flex flex-col sm:flex-row gap-3">

                            <div class="relative">
                                <button class="inline-flex items-center px-4 py-2 bg-gray-100  rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>Category</span>
                                </button>
                                <ul class="dropdown-menu absolute z-10 mt-1 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    @forelse($categories as $category)
                                        <li>
                                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 " href="{{route('categories.show',$category->id)}}">
                                                {{ $category->name }}
                                                </a>
                                        </li>
                                    @empty
                                        <li>
                                            <span class="block px-4 py-2 text-sm text-gray-500 dark:text-gray-400">No categories found</span>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>

                            <!-- Month Filter -->
                            <div class="relative">
                                <button class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-600 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>Choose Month</span>
                                    <svg class="w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <ul class="dropdown-menu absolute z-10 mt-1 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    @php
                                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                    @endphp
                                    @for($i=1; $i<=12; $i++)
                                        <li>
                                            <form action="{{route('expenses.index',$i)}}" method="get">
                                                @csrf
                                                <input type="hidden" name="month" value="{{$i}}">
                                                <button class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600" type="submit">
                                                    {{ $months[$i-1] }}
                                                </button>
                                            </form>
                                        </li>
                                    @endfor
                                </ul>
                            </div>
                        </div>



                        <form action="{{route('expenses.create')}}" method="get">
                            @csrf
                            <button type="submit">New expenses</button>
                        </form>
                    </div>

                    <!-- Expenses List -->
                    <div class="mt-6 space-y-4">
                        @forelse ($expenses as $expense)
                            <div class="bg-gray-50 dark:bg-gray-700 overflow-hidden border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm hover:shadow transition-shadow duration-200">
                                <div class="px-4 py-3 bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-600 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $expense->category->name }}</h3>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 sm:mt-0">
                                        <span class="inline-flex items-center">
                                            {{ $expense->date }}
                                        </span>
                                    </p>
                                </div>
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xl font-bold text-gray-800 dark:text-gray-200">
                                            Rs {{ number_format($expense->amount, 2) }}
                                        </p>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('expenses.edit', $expense->id) }}" class="inline-flex items-center px-3 py-1 text-xs font-medium ">

                                                Edit
                                            </a>
                                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1 text-xs " onclick="return confirm('Are you sure you want to delete this expense?')">

                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty

                        @endforelse
                    </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
