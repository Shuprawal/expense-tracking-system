
<div class="mt-4">
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="monthDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Choose Month
        </button>
        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="monthDropdown">

            @foreach(range(1, 12) as $i)
                <li>
                    <form action="{{ route($route, $parameters ?? []) }}" method="get">
                        <input type="hidden" name="month" value="{{ $i }}">

                        <button class="dropdown-item" type="submit">
                            {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
</div>
