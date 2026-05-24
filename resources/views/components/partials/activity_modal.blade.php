@props(['partners', 'requestStatuses', 'priorities', 'initiatives', 'activityStatuses', 'directorates'])

<div class="modal fade" id="activity_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="support_modal_title">Update Activity Request</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="activity_form">
                @csrf
                <input type="hidden" id="support_method" value="PUT">
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="initiative_id">Initiative</label>
                                    <select name="initiative_id" class="form-control select2" id="sr_initiative_id">
                                        <option value="">Select Initiative</option>
                                        @foreach($initiatives as $initiative)
                                            <option value="{{ $initiative->id }}">{{ $initiative->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="partner_id">Partner</label>
                                    <select name="partner_id" class="form-control select2" id="sr_partner_id">
                                        <option value="">Select Partner</option>
                                        @foreach($partners as $partner)
                                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="interested_partners">Interested Partners</label>
                                    <select name="interested_partners[]" class="form-control select2" id="sr_interested_partners" multiple="multiple" data-placeholder="Select Interested Partners">
                                        @foreach($partners as $partner)
                                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sr_directorates">Directorates</label>
                                    <select name="directorates[]" class="form-control select2" id="sr_directorates" multiple="multiple" data-placeholder="Select Directorates">
                                        @foreach($directorates as $directorate)
                                            <option value="{{ $directorate->id }}">{{ $directorate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="request_status_id">Request Status</label>
                                    <select name="request_status_id" class="form-control select2" id="sr_request_status_id">
                                        <option value="">Select Status</option>
                                        @foreach($requestStatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Priority<span class="required-field">*</span></label>
                                    <select name="priority" class="form-control select2" id="sr_priority" required>
                                        <option value="">Select Priority</option>
                                        @foreach($priorities as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" name="start_date" class="form-control" id="sr_start_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" name="end_date" class="form-control" id="sr_end_date">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="budget">Budget</label>
                                    <input type="text" name="budget" class="form-control" id="sr_budget" placeholder="Enter budget">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="completion">Completion (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" name="completion" class="form-control" id="sr_completion" placeholder="Enter completion percentage">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="activity_status_id">Activity Status</label>
                                    <select name="activity_status_id" class="form-control select2" id="sr_activity_status_id">
                                        <option value="">Select Activity Status</option>
                                        @foreach($activityStatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="request_type">Request Type</label>
                                    <select name="request_type" class="form-control select2" id="sr_request_type">
                                        <option value="">Select Request Type</option>
                                        <option value="New">New</option>
                                        <option value="Current">Current</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="expenditure">Expenditure Details</label>
                                    <textarea name="expenditure" class="form-control" id="sr_expenditure" rows="3" placeholder="Enter Expenditure Details"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="activities">Description<span class="required-field">*</span></label>
                                    <textarea name="activities" class="form-control" id="sr_activities" rows="4" placeholder="Enter Description" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="hidden" name="activity_id" id="activity_id">
                    <button type="submit" class="btn btn-info">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .required-field { color: red; margin-left: 4px; }
</style>
