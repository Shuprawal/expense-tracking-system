<div>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <form class="d-flex" role="search" method="get" action="{{route($route,$parameter = [])}}">
                <input class="form-control me-2" type="search" value="{{old('search')}}" name="inputText" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </nav>
</div>


