
<div class="container my-3">
    <nav class="navbar bg-light shadow-sm rounded p-2">
        <div class="container-fluid">
{{--            @dd($route,$parameters)--}}
            <form class="d-flex w-100" role="search" method="get" action="{{ route($route, $parameters ?? []) }}">
                <input class="form-control me-2 rounded-pill" type="search" value="{{ request('inputText') }}" name="inputText" placeholder="Search..." aria-label="Search">
                @foreach($parameters as $key => $value)
                    @if($key !== 'inputText')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <button class="btn btn-dark rounded-pill px-4" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </nav>
</div>


