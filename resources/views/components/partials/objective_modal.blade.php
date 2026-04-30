@props(['themes'])
<!-- /.modal -->
<div class="modal fade" id="update_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Objective Detail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="objective_update_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Objective Name<span class="required-field">*</span></label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Objective Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="theme_id">Theme<span class="required-field">*</span></label>
                                <select name="theme_id" class="form-control" id="theme_id">
                                    <option value="">Select Theme</option>
                                    @foreach($themes as $theme)
                                        <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="hidden" name="objective_id" id="objective_id">
                    <button type="submit" class="btn btn-info">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .required-field {
        color: red;
        margin-left: 4px;
    }
</style>
