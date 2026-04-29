@can($permission_view)
<a class='btn btn-sm' data-row_id="{{ $row_id }}" role="button" id="update_row">
  <span>View</span> <i class='text-info far fa-eye'></i></a>
@endcan

@can($permission_delete)
<a class='btn btn-sm' onclick="delete_exception(this, '{{ $row_id }}')" role="button">
  <i class='text-danger fas fa-trash'></i></a>
@endcan