@can('partner: send-update')
    <a class="btn btn-sm btn-primary send-update-btn mx-1" data-row_id="{{ $row_id }}" role="button">
        <i class="fas fa-envelope mr-1"></i> Send Update
    </a>
@endcan

@can('partner: view')
    <a class='btn btn-sm' data-row_id="{{ $row_id }}" role="button" id="show_row">
        <i class='text-info far fa-eye'></i>
    </a>
@endcan

@can('partner: edit')
    <a class='btn btn-sm' data-row_id="{{ $row_id }}" role="button" id="update_row">
        <i class='text-info far fa-edit'></i>
    </a>
@endcan

@can('partner: delete')
    <a class='btn btn-sm' onclick="delete_row(this, '{{ $row_id }}')" role="button">
        <i class='text-danger fas fa-trash'></i>
    </a>
@endcan
