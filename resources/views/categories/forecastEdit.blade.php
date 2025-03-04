<x-app-layout>
    <div class="container">
        <h2>Edit percentage</h2>

        <form action="{{route('forecast.percentage')}}" method="POST" class="flex flex-col">
            @csrf
            @foreach ($categories as $category)
                <div>
                    <input type="hidden" name="category[]" value="{{$category->id}}">
                    <input type="number" name="percentage[]" value="{{old('percentage')}}" class="m-2 p-2">
                    {{$category->name}}
                    <x-input-error :messages="$errors->get('percentage.' .$loop->index)" class="mt-2" />
                </div>
            @endforeach

            <button type="submit" class="m-2 p-2 btn btn-primary">Submit</button>
        </form>


    </div>
</x-app-layout>
