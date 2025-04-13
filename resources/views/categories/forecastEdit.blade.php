<x-app-layout>
    <div class="container">
        <h2>Edit percentage</h2>

        <form action="{{route('forecastUpdate.percentage')}}" method="POST" class="flex flex-col">
            @csrf
                <div>
                    <input type="hidden" name="start" value="{{$start}}">
                    <input type="hidden" name="end" value="{{$end}}">
                    <input type="hidden" name="category" value="{{$categories->pivot->category_id}}">
                    <input type="hidden" name="oldPercentage" value="{{$categories->pivot->percentage}}">
                    <input type="number" name="percentage" value="{{old('percentage',$categories->pivot->percentage)}}" class="m-2 p-2">
                    {{$categories->name}}
                    <x-input-error :messages="$errors->get('percentage' )" class="mt-2" />
                </div>


            <button type="submit" class="m-2 p-2 btn btn-primary">Submit</button>
        </form>


    </div>
</x-app-layout>
