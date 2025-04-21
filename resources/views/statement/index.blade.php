<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h3 fw-bold text-primary"> Transactions</h1>
                            <x-dateDuration :route="'statements.index'" />
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-primary">
                                <tr>
                                    <th scope="col">Transaction Type</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($statements as $statement)
                                    <tr>
                                        <td>
                                                <span class="badge bg-secondary">
                                                    {{ class_basename($statement->statementable_type) }}
                                                </span>
                                        </td>
                                        <td>
                                            @if($statement->statementable_type == 'App\Models\Expense')
                                                <span class="text-danger fw-semibold">- ${{ number_format($statement->amount, 2) }}</span>
                                            @else
                                                <span class="text-success fw-semibold">+ ${{ number_format($statement->amount, 2) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-muted">{{ \Carbon\Carbon::parse($statement->date)->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No transactions found for the selected duration.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
