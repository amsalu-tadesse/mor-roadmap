@if($status =='1')
<span id="nikebtn" class="fa fa-check-circle" style="color: green;"></span>
@else
<a class='btn btn-sm' onclick="fixorNot_exception(this, '{{ $row_id }}')" role="button">
    <span id="xbtn" class="fa fa-times-circle" style="color: red;"></span>
</a>
@endif