<x-app-layout>


    <div class="container m-4 p-4">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">categories</th>
{{--                            <th scope="col">percentage</th>--}}


                        </tr>
                        </thead>
                        <tbody>

                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->username }}</td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 20px;">
                                        @forelse($user->categories as $category)
                                            <div>
                                                {{ $category->name }} - {{ $category->pivot->percentage }}% ({{ \Carbon\Carbon::parse($category->pivot->date)->format('F')  }})
                                            </div>
                                        @empty
                                            <p>No Category Selected</p>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                        @endforeach





                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




</x-app-layout>
