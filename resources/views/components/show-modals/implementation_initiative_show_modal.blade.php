<div class="modal fade" id="show_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Implementation Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <h5 class="text-info border-bottom pb-2 mb-3">Base Initiative Details</h5>
                    <table class="table table-bordered mb-4">
                        <tr>
                            <th style="width: 30%">Initiative Name</th>
                            <td id="name_show"></td>
                        </tr>
                        <tr>
                            <th>Directorate</th>
                            <td id="directorate_show"></td>
                        </tr>
                        <tr>
                            <th>Theme</th>
                            <td id="theme_show"></td>
                        </tr>
                        <tr>
                            <th>Objective</th>
                            <td id="objective_show"></td>
                        </tr>
                    </table>

                    <h5 class="text-info border-bottom pb-2 mb-3">Implementation Details</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Start Date</th>
                            <td id="start_date_show"></td>
                        </tr>
                        <tr>
                            <th>End Date</th>
                            <td id="end_date_show"></td>
                        </tr>
                        <tr>
                            <th>Budget</th>
                            <td id="budget_show"></td>
                        </tr>
                        <tr>
                            <th>Expenditure</th>
                            <td id="expenditure_show"></td>
                        </tr>
                        <tr>
                            <th>Partner</th>
                            <td id="partner_show"></td>
                        </tr>
                        <tr>
                            <th>Completion</th>
                            <td id="completion_show"></td>
                        </tr>
                        <tr>
                            <th>Initiative Status</th>
                            <td id="initiative_status_show"></td>
                        </tr>
                        <tr>
                            <th>Request Type</th>
                            <td id="request_show"></td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td id="created_by"></td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td id="created_at"></td>
                        </tr>
                    </table>

                    <h5 class="text-info border-bottom pb-2 mb-3 mt-4">Support Requests</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped" id="support_requests_show_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Partner</th>
                                    <th>Activities</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- To be populated by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
