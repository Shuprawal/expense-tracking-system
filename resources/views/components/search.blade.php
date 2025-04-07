
<div class="container my-3">
    <nav class="navbar bg-light shadow-sm rounded p-2">
        <div class="container-fluid">
{{--            @dd($route,$parameters)--}}
            <form class="d-flex w-100" role="search" method="get" action="{{ route($route, $parameters ?? []) }}">
                <input class="form-control me-2 rounded-pill" type="search" value="{{ request('inputText') }}" name="inputText" placeholder="Search..." aria-label="Search">
                <button class="btn btn-primary rounded-pill px-4" type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>
    </nav>
</div>


