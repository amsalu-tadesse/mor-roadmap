@props(['levels'])


<!-- Modal -->
<div class="modal fade" id="subscription_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Organization Levels</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      @foreach($levels as $level)
                        <input type="checkbox" value="{{$level->id}}" name="level" /> {{$level->name}}<br>
                        @endforeach
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="subscribe">Submit</button>
      </div>
    </div>
  </div>
</div>
