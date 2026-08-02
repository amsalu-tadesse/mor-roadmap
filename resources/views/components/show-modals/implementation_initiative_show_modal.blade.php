@props(['initiativeActivitiesShowTable'])

<div class="modal fade" id="show_modal">
    <div class="modal-dialog modal-xl">
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
                    <table class="table table-bordered mb-4" style="table-layout: fixed; width: 100%;">
                        <tr>
                            <th style="width: 30%">Initiative Name</th>
                            <td id="name_show" style="word-break: break-word;"></td>
                        </tr>
                        <tr>
                            <th>Directorates</th>
                            <td id="directorate_show" style="word-break: break-word;"></td>
                        </tr>
                        <tr>
                            <th>Theme</th>
                            <td id="theme_show" style="word-break: break-word;"></td>
                        </tr>
                        <tr>
                            <th>Objective</th>
                            <td id="objective_show" style="word-break: break-word;"></td>
                        </tr>
                        <tr>
                            <th>Note</th>
                            <td id="note_show" style="word-break: break-word; white-space: pre-wrap;"></td>
                        </tr>
                        <tr id="approval_status_row">
                            <th>Approval Status</th>
                            <td id="approval_status_show" style="word-break: break-word;"></td>
                        </tr>
                    </table>

                    <div id="approval_history_section" class="mt-4" style="display: none;">
                        <h5 class="font-weight-bold text-secondary mb-2"><i class="fas fa-history mr-1"></i> Approval Cycle History</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 8%">Cycle</th>
                                        <th style="width: 10%">Status</th>
                                        <th>Proposal Description</th>
                                        <th style="width: 15%">Attachment</th>
                                        <th>Decision Remarks</th>
                                        <th style="width: 15%">Date</th>
                                    </tr>
                                </thead>
                                <tbody id="approval_history_tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <h5 class="text-info border-bottom pb-2 mb-3 mt-4">Activities</h5>
                    <input type="hidden" id="show_initiative_id" value="">
                    <x-partials.initiative_activities_datatable :dataTable="$initiativeActivitiesShowTable" />
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
