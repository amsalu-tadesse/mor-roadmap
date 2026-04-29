@if ($status == 1)
    <span id="nikebtn" class="fa fa-check-circle" style="color: green;">New</span>
@elseif ($status >= 4 and isset($weight))
    <div class="text-center">
        <input style="outline: none; border: none" type="text" class="text-center issue_percentage_knob"
            data-readonly="true" value="{{ $weight . '%' }}" data-width="50" data-height="50" data-fgColor="#39CCCC"
            readonly>
    </div>
@elseif ($status == 2 and isset($weight))
    <span id="nikebtn" class="fa fa-check-circle" style="color: orange;">Requested</span>
@elseif ($status == 3)
    <a onclick="issue_reject_message(this, '{{ $row_id }}')" role="button">
        <span id="nikebtn" class="fa fa-times-circle" style="color: red;">Rejected</span></a>
@else
    <a class='btn btn-sm btn-success' onclick="issue_approve(this, '{{ $row_id }}')" role="button">Respond
    </a>
@endif
