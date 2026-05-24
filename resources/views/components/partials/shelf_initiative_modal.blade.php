@props(['objectives', 'directorates', 'implementationStatuses', 'themes', 'initiativeActivitiesEditTable'])

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
                                    <label for="directorates">Directorates<span class="required-field text-danger">*</span></label>
                                    <select name="directorates[]" class="form-control select2" id="directorates" multiple="multiple" data-placeholder="Select Directorates" required>
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
                                    <label for="theme_id_modal">Theme<span class="required-field text-danger">*</span></label>
                                    <select name="theme_id" class="form-control select2" id="theme_id_modal" required>
                                        <option value="">Select Theme</option>
                                        @foreach($themes as $theme)
                                            <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="objective_id_modal">Objective<span class="required-field text-danger">*</span></label>
                                    <select name="objective_id" class="form-control select2" id="objective_id_modal" required>
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
                                    <label for="implementation_status_id">Implementation Status</label>
                                    <select name="implementation_status_id" class="form-control select2" id="implementation_status_id">
                                        <option value="">Select Implementation Status</option>
                                        @foreach($implementationStatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea name="note" class="form-control" id="note" rows="1"></textarea>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5>Activities</h5>
                            <button type="button" class="btn btn-sm btn-primary" id="add_activity">
                                <i class="fas fa-plus"></i> Add Activity
                            </button>
                        </div>
                        <x-partials.initiative_activities_datatable :dataTable="$initiativeActivitiesEditTable" />
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

<script>
    $(document).ready(function () {
        $('#theme_id_modal').on('change', function () {
            var themeId = $(this).val();
            var targetObjectiveId = $(this).data('selected-objective');
            if (themeId) {
                $.ajax({
                    url: "{{ route('admin.get-objectives-by-theme') }}",
                    type: "GET",
                    data: { theme_id: themeId },
                    dataType: "json",
                    success: function (data) {
                        $('#objective_id_modal').empty();
                        $('#objective_id_modal').append('<option value="">Select Objective</option>');
                        $.each(data, function (key, value) {
                            $('#objective_id_modal').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        if (targetObjectiveId) {
                            $('#objective_id_modal').val(targetObjectiveId).trigger('change');
                            $('#theme_id_modal').removeData('selected-objective');
                        }
                    }
                });
            } else {
                $('#objective_id_modal').empty();
                $('#objective_id_modal').append('<option value="">Select Objective</option>');
            }
        });
    });
</script>
