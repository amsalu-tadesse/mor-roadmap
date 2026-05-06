@props(['objectives', 'directorates', 'implementationStatuses'])

<div class="modal fade" id="update_modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Shelf Initiative</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="shelf_initiative_update_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Initiative Name<span class="required-field text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" id="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="objective_id">Objective<span class="required-field text-danger">*</span></label>
                                    <select name="objective_id" class="form-control" id="objective_id" required>
                                        <option value="">Select Objective</option>
                                        @foreach($objectives as $objective)
                                            <option value="{{ $objective->id }}">{{ $objective->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="directorate_id">Directorate<span class="required-field text-danger">*</span></label>
                                    <select name="directorate_id" class="form-control" id="directorate_id" required>
                                        <option value="">Select Directorate</option>
                                        @foreach($directorates as $directorate)
                                            <option value="{{ $directorate->id }}">{{ $directorate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="implementation_status_id">Implementation Status</label>
                                    <select name="implementation_status_id" class="form-control" id="implementation_status_id">
                                        <option value="">Select Implementation Status</option>
                                        @foreach($implementationStatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea name="note" class="form-control" id="note" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5>Support Requests</h5>
                            <button type="button" class="btn btn-sm btn-primary" id="add_support_request">
                                <i class="fas fa-plus"></i> Add Support Request
                            </button>
                        </div>
                        <table class="table table-sm table-bordered" id="shelf-support-requests-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Partner</th>
                                    <th>Activities</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Populated via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="hidden" name="initiative_id" id="initiative_id">
                    <button type="submit" class="btn btn-info">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
