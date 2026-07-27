@can('shelf-initiative: approve')
    <a class="btn btn-sm btn-warning text-white propose-approve-btn mx-1" data-row_id="{{ $row_id }}" role="button">Approve</a>
@endcan

@can('shelf-initiative: accept-approve')
    <a class="btn btn-sm btn-success text-white accept-approve-btn mx-1" data-row_id="{{ $row_id }}" role="button">Approve</a>
@endcan

@can('shelf-initiative: view')
    <a class='btn btn-sm' data-row_id="{{ $row_id }}" role="button" id="show_row">
        <i class='text-info far fa-eye'></i>
    </a>
@endcan

@can('shelf-initiative: edit')
    <a class='btn btn-sm' data-row_id="{{ $row_id }}" role="button" id="update_row">
        <i class='text-info far fa-edit'></i>
    </a>
@endcan

@can('shelf-initiative: delete')
    <a class='btn btn-sm' onclick="delete_row(this, '{{ $row_id }}')" role="button">
        <i class='text-danger fas fa-trash'></i>
    </a>
@endcan
