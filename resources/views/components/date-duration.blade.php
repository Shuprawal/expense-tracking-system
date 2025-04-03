<div>
    <form class="d-flex align-items-center gap-3" action="{{route($route,$parameters ??[])}}" method="get">
        <input type="date"  value="{{old('start')}}" name="start">
        <input type="date"   value="{{old('end')}}" name="end">
        <button class="btn btn-primary"  type="submit">Select date</button>
    </form>
</div>
