@props(['partners', 'initiativeStatuses', 'objectives', 'directorates', 'implementationStatuses', 'themes'])

<div class="modal fade" id="update_modal" data-backdrop="static">
    <div class="modal-dialog modal-xl">
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
                        <h5 class="text-info border-bottom pb-2 mb-3">Base Initiative Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Initiative Name<span class="required-field text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" id="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="directorate_id">Directorate<span class="required-field text-danger">*</span></label>
                                    <select name="directorate_id" class="form-control select2" id="directorate_id" required>
                                        <option value="">Select Directorate</option>
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
                                    <select name="implementation_status_id" class="form-control " id="implementation_status_id">
                                        <option value="">Select Implementation Status</option>
                                        @foreach($implementationStatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-info border-bottom pb-2 mb-3 mt-4">Implementation Details</h5>
                        <div class="row">


                           <div class="col-md-3">
                                <div class="form-group">
                                    <label for="partner_id">Partner</label>
                                    <select name="partner_id" class="form-control " id="partner_id">
                                        <option value="">Select Partner</option>
                                        @foreach($partners as $partner)
                                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <div class="input-group date" id="edit_start_date_group" data-target-input="nearest">
                                        <input type="text" name="start_date" class="form-control datetimepicker-input" data-target="#edit_start_date_group" id="start_date" autocomplete="off">
                                        <div class="input-group-append" data-target="#edit_start_date_group" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <div class="input-group date" id="edit_end_date_group" data-target-input="nearest">
                                        <input type="text" name="end_date" class="form-control datetimepicker-input" data-target="#edit_end_date_group" id="end_date" autocomplete="off">
                                        <div class="input-group-append" data-target="#edit_end_date_group" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="completion">Completion (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" name="completion" class="form-control" id="completion" placeholder="0 - 100">
                                </div>
                            </div>

                        </div>



                        <div class="row">


                           <div class="col-md-4">
                                <div class="form-group">
                                    <label for="budget">Budget</label>
                                    <input type="text" name="budget" class="form-control" id="budget" placeholder="e.g. 10,000">
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
                                    <select name="request" class="form-control " id="request">
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea name="note" class="form-control" id="note" rows="4" placeholder="Enter Note"></textarea>
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

<script>
    $(document).ready(function () {

      // Fire this logic only after the modal is completely visible
  /*  $('#update_modal').on('shown.bs.modal', function () {
        $('.select2').select2({
            dropdownParent: $('#update_modal')
        });
    });

    // Destroy select2 when modal closes to prevent memory leaks and duplication
    $('#update_modal').on('hidden.bs.modal', function () {
        $('.select2').select2('destroy');
    });*/


        $('#theme_id_modal').on('change', function () {
            console.log('********Theme changed, fetching objectives...'); // Debugging line
            var themeId = $(this).val();
            var targetObjectiveId = $(this).data('selected-objective');
            if (themeId) {
                $.ajax({
                    url: "{{ route('admin.get-objectives-by-theme') }}",
                    type: "GET",
                    data: { theme_id: themeId },
                    dataType: "json",
                    success: function (data) {

                        console.log('*********Received objectives:', data); // Debugging line
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
                console.log('No theme selected, clearing objectives'); // Debugging line
                $('#objective_id_modal').empty();
                $('#objective_id_modal').append('<option value="">Select Objective</option>');
            }
        });
        console.log('***********Implementation Initiative Modal script loaded'); // Debugging line





    });
</script>
