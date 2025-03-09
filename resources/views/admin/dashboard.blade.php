<x-app-layout>

    <div class="container m-4 py-12 row">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3> Total number of user : {{ $userNumber }}</h3>
                        <h3>Total number of categories : {{$categoryNumber}}</h3>
                        <h3>Average income for this month :{{{$averageIncome}}}</h3>
                        <h3>Most used categories:</h3>
                        @foreach($mostUsedCategories as $mostUsedCategory)
                            {{$mostUsedCategory->name}} <br>
                        @endforeach
                        <h3></h3>
                    </div>
                </div>
            </div>
        </div>


    </div>

</x-app-layout>
