<div>
    <form class="d-flex align-items-center gap-3" action="{{route($route,$parameters ??[])}}" method="get">
        <div>
            <input type="date"  value="{{old('start')}}" name="start">
            <x-input-error :messages="$errors->get('start')" class="mt-2" />
        </div>
        <div>
            <input type="date"   value="{{old('end')}}" name="end">
            <x-input-error :messages="$errors->get('end')" class="mt-2" />
        </div>

        <button class="btn btn-primary"  type="submit">Select date</button>
    </form>
</div>
