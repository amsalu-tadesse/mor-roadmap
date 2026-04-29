@isset($deleted)
<a class='btn btn-sm' onclick="restore(this, '{{ $row_id }}')" role="button">
<i class='text-success fas fa-undo'>&nbsp; Restore</i></a>
<!-- <a class='btn btn-sm' onclick="permanet_delete(this, '{{ $row_id }}')" role="button">
<i class='text-danger fas fa-trash'></i></a> -->
@endisset