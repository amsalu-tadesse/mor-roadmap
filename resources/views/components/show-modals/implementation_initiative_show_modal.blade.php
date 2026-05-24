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
                    <table class="table table-bordered mb-4">
                        <tr>
                            <th style="width: 30%">Initiative Name</th>
                            <td id="name_show"></td>
                        </tr>
                        <tr>
                            <th>Directorates</th>
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
