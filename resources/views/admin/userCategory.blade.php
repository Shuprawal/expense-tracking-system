<x-app-layout>
<div class="container m-2 p-2">
    <h3>List of {{$userCount}} users associated with {{$category->name}} category:</h3>
    @forelse($users as $user)
        {{$user->username}}
        {{$user->email}}

    @empty
        No users!!
    @endforelse
</div>
</x-app-layout>
