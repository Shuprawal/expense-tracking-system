<div>
    <form action="{{route($route,$parameters ?? [])}}" method="post">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
    </form>
</div>
