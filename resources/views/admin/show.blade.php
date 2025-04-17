<x-app-layout>
    <div class="container my-4">
        <h3 class="mb-3">Profile</h3>
        <div class="card shadow-sm border-0 rounded-lg mb-4">
            <div class="d-flex align-items-center p-4">
                <div class="me-4">
                    <i class="bi bi-person-circle fs-1 text-secondary"></i>
                </div>
                <div>
                    <h5 class="mb-1">
                        {{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }}
                    </h5>
                    <p class="mb-0 text-muted">{{ $user->email }}</p>
                    <p class="mb-0 text-muted">{{ $user->username }}</p>
                    <small class="badge bg-primary">
                        {{ ucfirst($user->roles->pluck('name')->implode(', ')) }}
                    </small>
                </div>
            </div>
        </div>
    <div class="container mb-3">
        <h3>list of expenses</h3>
        <div>

            <div class="row">
                @forelse($user->expenses as $expense)
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 rounded-lg mb-3">
                            <div class="card-header bg-dark text-white d-flex justify-content-between">
                                <span>{{ $expense->category->name }}</span>
                                <span class="badge bg-light text-dark">{{ $expense->date }}</span>
                            </div>
                            <div class="card-body">
                                <p class="fw-bold text-primary">${{ number_format($expense->amount, 2) }}</p>
                                <p class="text-muted">{{ $expense->description }}</p>
                                <div class="d-flex justify-content-between mt-3">
{{--                                    <x-delete-button :route="'expenses.destroy'" :parameters="$expense->id" />--}}
{{--                                    <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-outline-dark btn-sm">Edit</a>--}}
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
        </div>

        </div>
    </div>
</x-app-layout>
