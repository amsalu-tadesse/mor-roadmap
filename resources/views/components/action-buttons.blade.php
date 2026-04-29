@isset($confirmed)
    @isset($crime)
        @auth
            @unless (Auth::user()->hasRole(['Super Admin', 'Supervisor']))
                <!-- Display view button only if user has no Super Admin or Supervisor role -->
                @can($permission_view)
                    <a class='btn btn-sm' href="{{ route($route_detail, [$row_id]) }}" role="button" id="show_row">
                        <i class='text-info far fa-eye'></i>
                    </a>
                @endcan
            @else
                <!-- Display view button if user is Super Admin or Supervisor -->
                @can($permission_view)
                    <a class='btn btn-sm' href="{{ route($route_detail, [$row_id]) }}" role="button" id="show_row">
                        <i class='text-info far fa-eye'></i>
                    </a>
                @endcan

                <!-- Display edit button -->

                @can($permission_edit)
                    <a href="{{ route($route, [$row_id]) }}"><i class='text-info far fa-edit'></i></a>
                @endcan

                <!-- Display delete button -->
                @can($permission_delete)
                    <a class='btn btn-sm' onclick="delete_row(this, '{{ $row_id }}')" role="button">
                        <i class='text-danger fas fa-trash'></i>
                    </a>
                @endcan
            @endunless
        @endauth
    @else
        <!-- Handle the case when $crime is not set -->
    @endisset
@else
    @isset($crime)
        @can($permission_view)
            <a class='btn btn-sm' href="{{ route($route_detail, [$row_id]) }}" role="button" id="show_row">
                <i class='text-info far fa-eye'></i></a>
        @endcan
    @endisset


    @isset($crime)
        @can($permission_edit)
            <a href="{{ route($route, [$row_id]) }}"><i class='text-info far fa-edit'></i></a>
        @endcan
    @else
        @can($permission_edit)
            <a class='btn btn-sm' data-row_id="{{ $row_id }}" role="button" id="update_row">
                <i class='text-info far fa-edit'></i></a>
        @endcan
    @endisset

    @isset($delete)
    @else
        @can($permission_delete)
            <a class='btn btn-sm' onclick="delete_row(this, '{{ $row_id }}')" role="button">
                <i class='text-danger fas fa-trash'></i></a>
        @endcan
    @endisset
@endisset

@isset($trashed)
    <a class='btn btn-sm' onclick="restore(this, '{{ $row_id }}')" role="button">
        <i class='text-success fas fa-undo'>&nbsp;Restore</i></a>
@endisset
