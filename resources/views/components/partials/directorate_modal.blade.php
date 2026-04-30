@props(['users'])

<!-- /.modal -->
<div class="modal fade" id="update_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Directorate Detail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="directorate_update_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Directorate Name<span class="required-field">*</span></label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Directorate Name">
                            </div>
                            <div class="form-group">
                                <label for="user_id">Director<span class="required-field">*</span></label>
                                <select name="user_id" class="form-control select2" id="user_id" style="width: 100%;">
                                    <option value="">Select Director</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="hidden" name="directorate_id" id="directorate_id">
                    <button type="submit" class="btn btn-info">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
