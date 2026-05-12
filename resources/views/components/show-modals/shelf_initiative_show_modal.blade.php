<div class="modal fade" id="show_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Shelf Initiative Detail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <table class="table table-bordered">
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
                        <tr>
                            <th>Note</th>
                            <td id="note_show"></td>
                        </tr>
                    </table>

                    <h5 class="mt-4">Support Requests</h5>
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
