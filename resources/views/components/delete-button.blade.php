{{--<div>--}}
{{--    <form action="{{route($route,$parameters ?? [])}}" method="post">--}}
{{--        @csrf--}}
{{--        @method('DELETE')--}}
{{--        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>--}}
{{--    </form>--}}
{{--</div>--}}




{{--<button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">--}}
{{--    <i class="bi bi-trash"></i>--}}
{{--</button>--}}


{{--<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog modal-dialog-centered">--}}
{{--        <div class="modal-content border-0 rounded-4 shadow">--}}
{{--            <div class="modal-header bg-danger text-white rounded-top-4">--}}
{{--                <h5 class="modal-title" id="deleteModalLabel">{{ $title }}</h5>--}}
{{--                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--            </div>--}}

{{--            <div class="modal-body">--}}
{{--                <p>{{ $message }}</p>--}}
{{--            </div>--}}

{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>--}}

{{--                <form action="{{ route($route, $parameters) }}" method="POST">--}}
{{--                    @csrf--}}
{{--                    @method('DELETE')--}}
{{--                    <button type="submit" class="btn btn-danger">Delete</button>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}



@php
    $modalId = 'deleteModal' . uniqid();
@endphp

<button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
    <i class="bi bi-trash"></i>
</button>

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p>{{ $message }}</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                <form action="{{ route($route, $parameters) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
