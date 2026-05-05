@props(['partners', 'requestStatuses', 'priorities', 'initiatives'])

<div class="modal fade" id="update_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Support Request</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="support_request_update_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="initiative_id">Initiative</label>
                                    <select name="initiative_id" class="form-control" id="initiative_id">
                                        <option value="">Select Initiative</option>
                                        @foreach($initiatives as $initiative)
                                            <option value="{{ $initiative->id }}">{{ $initiative->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="partner_id">Partner<span class="required-field">*</span></label>
                                    <select name="partner_id" class="form-control" id="partner_id" required>
                                        <option value="">Select Partner</option>
                                        @foreach($partners as $partner)
                                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="request_status_id">Request Status<span class="required-field">*</span></label>
                                    <select name="request_status_id" class="form-control" id="request_status_id" required>
                                        <option value="">Select Status</option>
                                        @foreach($requestStatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Priority<span class="required-field">*</span></label>
                                    <select name="priority" class="form-control" id="priority" required>
                                        <option value="">Select Priority</option>
                                        @foreach($priorities as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="activities">Activities<span class="required-field">*</span></label>
                                    <textarea name="activities" class="form-control" id="activities" rows="4" placeholder="Enter Activities" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="hidden" name="support_request_id" id="support_request_id">
                    <button type="submit" class="btn btn-info">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .required-field { color: red; margin-left: 4px; }
</style>
