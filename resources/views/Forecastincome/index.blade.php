
<x-app-layout>
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-primary">Income</h1>
            <a href="{{ route('incomes.create') }}" class="btn btn-lg btn-outline-dark shadow">+ Add Income</a>
        </div>

        <div class="d-flex flex-wrap gap-3 mb-4">

            <x-search :route="'forecasts.index'"
            :parameters="[
                'inputText'=>request('inputText'),
                'start'=>request('start'),
                'end'=>request('end')
            ]"
            />
{{--            @php--}}
{{--                $filteredParameters = array_filter([--}}
{{--                    'inputText' => request('inputText'),--}}
{{--                    'start' => request('start'),--}}
{{--                    'end' => request('end'),--}}
{{--                    --}}
{{--                ], fn($value) => filled($value));--}}
{{--            @endphp--}}


{{--            <x-search :route="'forecasts.index'" :parameters="$filteredParameters" />--}}
            <x-date-duration :route="'forecasts.index'" :parameters="[
                'search'=>$search,
//                'start'=>request('start'),
//                'end'=>request('end')

            ]" />
        </div>


        <div class="row">
            @forelse($incomes as $income)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0 rounded-lg mb-3">
                        <div class="card-header bg-dark text-white d-flex justify-content-between">

                            <span class="badge bg-light text-dark">{{ $income->date }}</span>
                        </div>
                        <div class="card-body">
                            <p class="fw-bold text-primary">${{ number_format($income->amount, 2) }}</p>
                            <p class="text-muted">{{ $income->description }}</p>
                            <div class="d-flex justify-content-between mt-3">
                                <x-delete-button :route="'incomes.destroy'" :parameters="$income->id" />
                                <a href="{{ route('incomes.edit', $income->id) }}" class="btn btn-outline-dark btn-sm">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">
                    <p>No expenses for this month</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $incomes->appends(['incomes' => request('incomes')])->links() }}
        </div>
    </div>
</x-app-layout>
