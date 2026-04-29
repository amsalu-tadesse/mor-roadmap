
@if($confirmed)
<span class='badge badge-success'>Confirmed</span>
@else
<a class='btn btn-sm' onclick="confirm(this, '{{ $row_id }}')" role="button">
<span class='badge badge-warning'>Pending</span></a>
@endif