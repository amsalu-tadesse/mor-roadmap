@props(['partners', 'initiativeStatuses'])

<div class="modal fade" id="update_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Implementation Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="implementation_initiative_update_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" name="start_date" class="form-control" id="start_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" name="end_date" class="form-control" id="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="budget">Budget</label>
                                    <input type="text" name="budget" class="form-control" id="budget" placeholder="e.g. $10,000 or 500,000 ETB">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="completion">Completion (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" name="completion" class="form-control" id="completion" placeholder="0 - 100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="partner_id">Partner</label>
                                    <select name="partner_id" class="form-control" id="partner_id">
                                        <option value="">Select Partner</option>
                                        @foreach($partners as $partner)
                                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="initiative_status_id">Initiative Status</label>
                                    <select name="initiative_status_id" class="form-control" id="initiative_status_id">
                                        <option value="">Select Status</option>
                                        @foreach($initiativeStatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="request">Request</label>
                                    <select name="request" class="form-control" id="request">
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
                                    <textarea name="expenditure" class="form-control" id="expenditure" rows="4" placeholder="Enter Expenditure Details"></textarea>
                                </div>
                            </div>
                        </div>
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
