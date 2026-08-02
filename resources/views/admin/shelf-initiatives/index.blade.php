<x-layout>
    <x-breadcrump title='Shelf Initiatives' parent='Shelf Initiatives' child='List' index="shelf-initiatives" />

    <div class='card'>
        <div class='card-header'>
            <div class="row">
                <div class='col-md-3'>
                    <div class="form-group">
                        <select id="filter_directorate" class="form-control select2">
                            <option value="">All Directorates</option>
                            @foreach($directorates as $directorate)
                                <option value="{{ $directorate->id }}">{{ $directorate->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class="form-group">
                        <select id="filter_theme" class="form-control select2">
                            <option value="">All Themes</option>
                            @foreach($themes as $theme)
                                <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class="form-group">
                        <select id="filter_objective" class="form-control select2">
                            <option value="">All Objectives</option>
                            @foreach($objectives as $objective)
                                <option value="{{ $objective->id }}">{{ $objective->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class="form-group">
                        <select id="filter_partner" class="form-control select2">
                            <option value="">All Partners</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class='card-body'>
            {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
        </div>
    </div>

    <x-partials.shelf_initiative_modal :objectives="$objectives" :directorates="$directorates"
        :implementationStatuses="$implementationStatuses" :themes="$themes"
        :initiativeActivitiesEditTable="$initiativeActivitiesEditTable" />
    <x-partials.activity_modal :partners="$partners" :priorities="$priorities" :initiatives="$initiatives"
        :activityStatuses="$activityStatuses" :directorates="$directorates" />
    <x-show-modals.shelf_initiative_show_modal :initiativeActivitiesShowTable="$initiativeActivitiesShowTable" />

    <x-partials.propose_approval_modal />
    <x-partials.accept_approval_modal />

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        {!! $initiativeActivitiesEditTable->html()->scripts() !!}
        {!! $initiativeActivitiesShowTable->html()->scripts() !!}
        <script>
            // Disable Bootstrap focus enforcement to prevent nested modal focus stealing (Select2)
            $.fn.modal.Constructor.prototype._enforceFocus = function () { };

            function reloadInitiativeActivitiesTable(tableId) {
                if (window.LaravelDataTables && window.LaravelDataTables[tableId]) {
                    window.LaravelDataTables[tableId].ajax.reload(null, false);
                }
            }
            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Shelf Initiative');
            }

            $(document).ready(function () {
                // Initialize filters (not in modal)
                $('.card-header .select2').select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });

                // Initialize modal selects
                // $('#update_modal .select2').select2({
                //     theme: 'bootstrap4',
                //     width: '100%',
                //      dropdownParent: $('#update_modal')
                // });


                $('#activity_modal').on('shown.bs.modal', function () {
                    // First ensure the initiative element is enabled so it can be initialized cleanly
                    $('#sr_initiative_id').prop('disabled', false);

                    // Initialize Select2
                    $(this).find('.select2').select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        dropdownParent: $('#activity_modal')
                    });

                    // Retrieve stored mode and act accordingly
                    var mode = $(this).data('mode');
                    if (mode === 'add') {
                        var initiativeId = $(this).data('initiative-id');
                        $('#sr_initiative_id').val(initiativeId).trigger('change');
                        $('#sr_initiative_id').prop('disabled', true);

                        // Clear all other select2 elements
                        $('#sr_partner_id').val('').trigger('change');
                        $('#sr_interested_partners').val([]).trigger('change');
                        $('#sr_directorates').val([]).trigger('change');
                        $('#sr_activity_status_id').val('').trigger('change');
                        $('#sr_request_type').val('').trigger('change');
                        $('#sr_priority').val('').trigger('change');
                    } else if (mode === 'edit') {
                        var data = $(this).data('activity-data');
                        if (data) {
                            $('#sr_directorates').data('selected-vals', data.directorates);

                            $('#sr_initiative_id').val(data.initiative_id).trigger('change');
                            $('#sr_initiative_id').prop('disabled', true);

                            $('#sr_partner_id').val(data.partner_id).trigger('change');
                            $('#sr_interested_partners').val(data.interested_partners).trigger('change');
                            $('#sr_activities').val(data.activities);
                            $('#sr_priority').val(data.priority ? data.priority.toString() : '').trigger('change.select2');
                            $('#sr_start_date').val(data.start_date);
                            $('#sr_end_date').val(data.end_date);
                            $('#sr_budget').val(data.budget);
                            $('#sr_completion').val(data.completion);
                            $('#sr_activity_status_id').val(data.activity_status_id ? data.activity_status_id.toString() : '').trigger('change.select2');
                            $('#sr_request_type').val(data.request_type ? data.request_type.toString() : '').trigger('change.select2');
                            $('#sr_expenditure').val(data.expenditure);
                        }
                    }
                });

                $(document).on('change', '#sr_initiative_id', function() {
                    var initiativeId = $(this).val();
                    if (initiativeId) {
                        $.ajax({
                            url: "{{ route('admin.get-directorates-by-initiative') }}",
                            type: "GET",
                            data: { initiative_id: initiativeId },
                            dataType: "json",
                            success: function(data) {
                                var select = $('#sr_directorates');
                                var selectedVals = select.data('selected-vals') || select.val() || [];
                                select.empty();
                                $.each(data, function(key, value) {
                                    select.append('<option value="' + value.id + '">' + value.name + '</option>');
                                });
                                select.val(selectedVals).trigger('change.select2');
                                select.removeData('selected-vals');
                            }
                        });
                    } else {
                        $('#sr_directorates').empty().trigger('change.select2');
                    }
                });
            });

            // Stacked modals scroll and backdrop fix
            $(document).on('hidden.bs.modal', '.modal', function () {
                if ($('.modal.show').length > 0) {
                    $('body').addClass('modal-open');
                } else {
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            });

            $('#activity_modal').on('hidden.bs.modal', function () {
                var modal = $(this);
                try {
                    modal.find('.select2').select2('destroy');
                } catch (e) {
                    console.warn('Select2 destroy ignored:', e);
                }
                modal.css('display', 'none');

                setTimeout(function () {
                    if ($('#update_modal').hasClass('show')) {
                        $('body').addClass('modal-open');
                        $('#update_modal').focus(); // Explicitly restore focus to parent modal
                        
                        // Clean up any remaining stacked backdrops safely
                        var backdrops = $('.modal-backdrop');
                        if (backdrops.length > 1) {
                            backdrops.slice(1).remove();
                        }
                    } else {
                        if ($('.modal.show').length === 0) {
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                        }
                    }
                }, 150);
            });

            $('#update_modal').on('shown.bs.modal', function () {
                $(this).find('.select2').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    dropdownParent: $('#update_modal')
                });
            });

            $(document).on('click', '#add_activity', function () {
                $('#activity_form')[0].reset();
                $('#support_modal_title').text('Add Activity Request');
                $('#activity_form').attr('action', "{{ route('admin.activities.store') }}");
                $('#support_method').val('POST');

                // Store state on modal
                $('#activity_modal').data('mode', 'add');
                $('#activity_modal').data('initiative-id', $('#initiative_id').val());

                $('#activity_modal').modal('show');
            });

            $(document).on('change', '#filter_directorate, #filter_objective, #filter_partner', function () {
                window.LaravelDataTables['shelf-initiatives-table'].ajax.reload();
            });

            // When theme filter changes, reload objective options then reload table
            $(document).on('change', '#filter_theme', function () {
                var themeId = $(this).val();
                if (themeId) {
                    $.ajax({
                        url: "{{ route('admin.get-objectives-by-theme') }}",
                        type: "GET",
                        data: { theme_id: themeId },
                        dataType: "json",
                        success: function (data) {
                            $('#filter_objective').empty();
                            $('#filter_objective').append('<option value="">All Objectives</option>');
                            $.each(data, function (key, value) {
                                $('#filter_objective').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                            if ($('#filter_objective').hasClass('select2-hidden-accessible')) {
                                $('#filter_objective').trigger('change.select2');
                            }
                            window.LaravelDataTables['shelf-initiatives-table'].ajax.reload();
                        }
                    });
                } else {
                    $('#filter_objective').empty();
                    $('#filter_objective').append('<option value="">All Objectives</option>');
                    @foreach($objectives as $objective)
                        $('#filter_objective').append('<option value="{{ $objective->id }}">{{ $objective->name }}</option>');
                    @endforeach
                        if ($('#filter_objective').hasClass('select2-hidden-accessible')) {
                        $('#filter_objective').trigger('change.select2');
                    }
                    window.LaravelDataTables['shelf-initiatives-table'].ajax.reload();
                }
            });

            $(document).on('click', '#update_row', function () {
                var row_id = $(this).data('row_id');
                var url = "{{ route('admin.shelf-initiatives.edit', ':id') }}";
                url = url.replace(':id', row_id);
                $('#shelf_initiative_update_form :input').not(':submit, :button, :hidden').val('');
                $('#directorates').val([]).trigger('change');
                $.ajax({
                    url: url, type: 'GET', dataType: 'json',
                    success: function (response) {
                        if (response.success == 1) {
                            var initiative = response.initiative;
                            $('#initiative_id').val(initiative.id);
                            $('#name').val(initiative.name);
                            $('#directorates').val(response.directorates).trigger('change');
                            $('#implementation_status_id').val(initiative.implementation_status_id).trigger('change');
                            $('#note').val(initiative.note);

                            // Load objectives for the selected theme, then set values
                            var themeId = initiative.theme_id || (initiative.objective ? initiative.objective.theme_id : null);
                            if (themeId) {
                                $('#theme_id_modal').data('selected-objective', initiative.objective_id);
                                $('#theme_id_modal').val(themeId).trigger('change');
                            } else {
                                $('#theme_id_modal').val('').trigger('change');
                                $('#objective_id_modal').val(initiative.objective_id).trigger('change');
                            }

                            reloadInitiativeActivitiesTable('initiative-activities-edit-table');

                            $('#update_modal').modal('show');
                        }
                    }
                });
            });

            $(document).on('click', '#show_row', function () {
                var row_id = $(this).data('row_id');
                var url = "{{ route('admin.shelf-initiatives.show', ':id') }}";
                url = url.replace(':id', row_id);
                $.ajax({
                    url: url, type: 'GET', dataType: 'json',
                    success: function (response) {
                        if (response.success == 1) {
                            $('#show_modal #name_show').html(response.initiative.name);
                            $('#show_modal #directorate_show').html(response.directorateName);
                            $('#show_modal #theme_show').html(response.themeName);
                            $('#show_modal #objective_show').html(response.objectiveName);
                            $('#show_modal #note_show').html(response.initiative.note ?? '');

                            if (response.initiative.approval_status) {
                                var statusHtml = response.initiative.approval_status;
                                if (statusHtml === 'requested' || statusHtml === 'proposed') {
                                    statusHtml = '<span class="fa fa-info-circle text-warning"> Requested</span>';
                                } else if (statusHtml === 'rejected') {
                                    statusHtml = '<span class="fa fa-times-circle" style="color:red"> Rejected</span>';
                                } else if (statusHtml === 'approved') {
                                    statusHtml = '<span class="fa fa-check-circle" style="color:green"> Approved</span>';
                                } else {
                                    statusHtml = statusHtml.charAt(0).toUpperCase() + statusHtml.slice(1);
                                }
                                $('#show_modal #approval_status_show').html(statusHtml);
                                $('#show_modal #approval_status_row').show();
                            } else {
                                $('#show_modal #approval_status_row').hide();
                            }

                            if (response.histories && response.histories.length > 0) {
                                var tbodyHtml = '';
                                var cycleMap = {};
                                $.each(response.histories, function(index, h) {
                                    if (!cycleMap[h.cycle_number]) {
                                        cycleMap[h.cycle_number] = {
                                            cycle_number: h.cycle_number,
                                            action: h.action,
                                            description: h.description,
                                            file_url: h.file_url,
                                            file_name: h.file_name,
                                            remarks: h.remarks,
                                            created_at: h.created_at
                                        };
                                    } else {
                                        if (h.action) cycleMap[h.cycle_number].action = h.action;
                                        if (h.description) cycleMap[h.cycle_number].description = h.description;
                                        if (h.file_url) {
                                            cycleMap[h.cycle_number].file_url = h.file_url;
                                            cycleMap[h.cycle_number].file_name = h.file_name;
                                        }
                                        if (h.remarks) cycleMap[h.cycle_number].remarks = h.remarks;
                                        if (h.created_at) cycleMap[h.cycle_number].created_at = h.created_at;
                                    }
                                });

                                $.each(cycleMap, function(cycleNum, h) {
                                    var actionBadge = '';
                                    if (h.action === 'requested') {
                                        actionBadge = '<span class="badge badge-warning">Requested</span>';
                                    } else if (h.action === 'rejected') {
                                        actionBadge = '<span class="badge badge-danger">Rejected</span>';
                                    } else if (h.action === 'approved') {
                                        actionBadge = '<span class="badge badge-success">Approved</span>';
                                    } else {
                                        actionBadge = '<span class="badge badge-secondary">' + h.action + '</span>';
                                    }

                                    var descriptionText = h.description || 'N/A';
                                    var remarksText = h.remarks || 'N/A';
                                    var fileLink = h.file_url ? '<a href="' + h.file_url + '" target="_blank" class="text-info font-weight-bold"><i class="fas fa-paperclip mr-1"></i>' + (h.file_name || 'Download') + '</a>' : '-';

                                    tbodyHtml += '<tr>' +
                                        '<td class="text-center font-weight-bold">Cycle #' + h.cycle_number + '</td>' +
                                        '<td>' + actionBadge + '</td>' +
                                        '<td>' + descriptionText + '</td>' +
                                        '<td>' + fileLink + '</td>' +
                                        '<td>' + remarksText + '</td>' +
                                        '<td>' + (h.created_at || '-') + '</td>' +
                                        '</tr>';
                                });

                                $('#approval_history_tbody').html(tbodyHtml);
                                $('#approval_history_section').show();
                            } else {
                                $('#approval_history_section').hide();
                            }

                            $('#show_initiative_id').val(row_id);
                            reloadInitiativeActivitiesTable('initiative-activities-show-table');

                            $('#show_modal').modal('show');
                        }
                    }
                });
            });

            $(document).on('submit', '#shelf_initiative_update_form', function (e) {
                e.preventDefault();
                var form_data = $(this).serialize();
                var row_id = $('#initiative_id', $(this)).val();
                var url = "{{ route('admin.shelf-initiatives.update', ':id') }}";
                url = url.replace(':id', row_id);
                $.ajax({
                    url: url, type: 'PATCH', data: form_data, dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            $('#update_modal').modal('toggle');
                            window.LaravelDataTables['shelf-initiatives-table'].ajax.reload();
                            toastr.success('You have successfully updated the Shelf Initiative.');
                        }
                    }
                });
            });

            $(document).on('click', '.edit-sr', function () {
                let id = $(this).data('id');
                let initiative_id = $(this).data('initiative_id');
                var url = "/admin/activities/" + id + "/edit";

                $('#support_modal_title').text('Edit Activity Request');
                $('#activity_form').attr('action', '/admin/activities/' + id);
                $('#support_method').val('PATCH');

                $.ajax({
                    url: url, type: 'GET', dataType: 'json',
                    success: function (response) {
                        if (response.success == 1) {
                            // Store state on modal
                            $('#activity_modal').data('mode', 'edit');
                            $('#activity_modal').data('activity-data', {
                                initiative_id: initiative_id,
                                partner_id: response.activity.partner_id,
                                interested_partners: response.interested_partners,
                                directorates: response.directorates,
                                activities: response.activity.activities,
                                priority: response.activity.priority,
                                start_date: response.activity.start_date ? response.activity.start_date.substring(0, 10) : '',
                                end_date: response.activity.end_date ? response.activity.end_date.substring(0, 10) : '',
                                budget: response.activity.budget,
                                completion: response.activity.completion,
                                activity_status_id: response.activity.activity_status_id,
                                request_type: response.activity.request_type,
                                expenditure: response.activity.expenditure
                            });
                            $('#activity_modal').modal('show');
                        }
                    }
                });
            });

            $(document).on('submit', '#activity_form', function (e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var method = $('#support_method').val();
                $('#sr_initiative_id').prop('disabled', false);
                var data = form.serialize();
                $('#sr_initiative_id').prop('disabled', true);

                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    success: function (response) {
                        if (response.success) {
                            $('#activity_modal').modal('hide');
                            toastr.success('Activity saved successfully');
                            reloadInitiativeActivitiesTable('initiative-activities-edit-table');
                        }
                    }
                });
            });

            $(document).on('click', '.delete-sr', function () {
                let id = $(this).data('id');
                let url = "/admin/activities/" + id;

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: { confirmButton: 'btn btn-success mx-1', cancelButton: 'btn btn-danger' },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: { _token: "{{ csrf_token() }}" },
                            dataType: 'json',
                            success: function (data) {
                                if (data.success) {
                                    swalWithBootstrapButtons.fire('Deleted!', 'Activity has been deleted.', 'success');
                                    reloadInitiativeActivitiesTable('initiative-activities-edit-table');
                                }
                            }
                        });
                    }
                });
            });

            // File input name helper
            $(document).on('change', '#propose_file', function () {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            // Yellow button (propose) handler
            $(document).on('click', '.propose-approve-btn', function () {
                var row_id = $(this).data('row_id');
                var form = $('#propose_approval_form');
                form[0].reset();
                form.find('.custom-file-label').removeClass("selected").html('Choose file');
                form.data('row_id', row_id);

                var url = "{{ route('admin.shelf-initiatives.show-approval', ':id') }}";
                url = url.replace(':id', row_id);
                $.ajax({
                    url: url, type: 'GET', dataType: 'json',
                    success: function (response) {
                        if (response.success == 1) {
                            var descHtml = '';
                            var fileHtml = '';
                            var remarksHtml = '';
                            var seenFiles = {};

                            if (response.histories && response.histories.length > 0) {
                                $.each(response.histories, function (i, h) {
                                    if (h.description) {
                                        descHtml += '<blockquote class="quote-info" style="margin-bottom: 5px; padding: 8px; background-color: #f8f9fa; border-left: 3px solid #17a2b8;">' + h.description + '</blockquote>';
                                    }
                                    if (h.file_url && !seenFiles[h.file_url]) {
                                        seenFiles[h.file_url] = true;
                                        fileHtml += '<div class="mb-1"><i class="fas fa-paperclip text-info mr-1"></i><a href="' + h.file_url + '" target="_blank" class="text-info font-weight-bold">' + (h.file_name || 'Download Attachment') + '</a></div>';
                                    }
                                    if (h.remarks) {
                                        remarksHtml += '<blockquote class="quote-warning" style="margin-bottom: 5px; padding: 8px; background-color: #fff8e6; border-left: 3px solid #ffc107;">' + h.remarks + '</blockquote>';
                                    }
                                });
                            } else {
                                if (response.approval_description) {
                                    descHtml = '<blockquote class="quote-info" style="margin-bottom: 5px; padding: 8px; background-color: #f8f9fa; border-left: 3px solid #17a2b8;">' + response.approval_description + '</blockquote>';
                                }
                                if (response.approval_file_url) {
                                    fileHtml = '<div class="mb-1"><i class="fas fa-paperclip text-info mr-1"></i><a href="' + response.approval_file_url + '" target="_blank" class="text-info font-weight-bold">' + (response.approval_file_name || 'Download Attachment') + '</a></div>';
                                }
                                if (response.approval_remarks) {
                                    remarksHtml = '<blockquote class="quote-warning" style="margin-bottom: 5px; padding: 8px; background-color: #fff8e6; border-left: 3px solid #ffc107;">' + response.approval_remarks + '</blockquote>';
                                }
                            }

                            if (descHtml) {
                                $('#propose_past_descriptions').html(descHtml).show();
                            } else {
                                $('#propose_past_descriptions').hide();
                            }

                            if (fileHtml) {
                                $('#propose_past_files').html(fileHtml).show();
                            } else {
                                $('#propose_past_files').hide();
                            }

                            if (remarksHtml) {
                                $('#propose_remarks_view').html(remarksHtml);
                                $('#propose_remarks_container').show();
                            } else {
                                $('#propose_remarks_container').hide();
                            }

                            $('#propose_description').val('');
                        }
                    }
                });

                $('#propose_approval_modal').modal('show');
            });

            // Yellow button form submission
            $(document).on('click', '.btn-submit-propose', function () {
                var decision = $(this).data('decision');
                $('#propose_decision').val(decision);

                var form = $('#propose_approval_form');
                if (!form[0].checkValidity()) {
                    form[0].reportValidity();
                    return;
                }

                var row_id = form.data('row_id');
                var url = "{{ route('admin.shelf-initiatives.propose-approval', ':id') }}";
                url = url.replace(':id', row_id);

                var formData = new FormData(form[0]);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            $('#propose_approval_modal').modal('hide');
                            window.LaravelDataTables['shelf-initiatives-table'].ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error('Failed to submit proposal.');
                        }
                    },
                    error: function (xhr) {
                        toastr.error('An error occurred during submission.');
                    }
                });
            });

            // Green button (accept/approve) handler
            $(document).on('click', '.accept-approve-btn', function () {
                var row_id = $(this).data('row_id');
                var form = $('#accept_approval_form');
                form[0].reset();
                form.data('row_id', row_id);

                var url = "{{ route('admin.shelf-initiatives.show-approval', ':id') }}";
                url = url.replace(':id', row_id);

                $.ajax({
                    url: url, type: 'GET', dataType: 'json',
                    success: function (response) {
                        if (response.success == 1) {
                            var descHtml = '';
                            var fileHtml = '';
                            var remarksHtml = '';
                            var seenFiles = {};

                            if (response.histories && response.histories.length > 0) {
                                $.each(response.histories, function (i, h) {
                                    if (h.description) {
                                        descHtml += '<blockquote class="quote-info" style="margin-bottom: 5px; padding: 8px; background-color: #f8f9fa; border-left: 3px solid #17a2b8;">' + h.description + '</blockquote>';
                                    }
                                    if (h.file_url && !seenFiles[h.file_url]) {
                                        seenFiles[h.file_url] = true;
                                        fileHtml += '<div class="mb-1"><i class="fas fa-paperclip text-info mr-1"></i><a href="' + h.file_url + '" target="_blank" class="text-info font-weight-bold">' + (h.file_name || 'Download Attachment') + '</a></div>';
                                    }
                                    if (h.remarks) {
                                        remarksHtml += '<blockquote class="quote-warning" style="margin-bottom: 5px; padding: 8px; background-color: #fff8e6; border-left: 3px solid #ffc107;">' + h.remarks + '</blockquote>';
                                    }
                                });
                            } else {
                                if (response.approval_description) {
                                    descHtml = '<blockquote class="quote-info" style="margin-bottom: 5px; padding: 8px; background-color: #f8f9fa; border-left: 3px solid #17a2b8;">' + response.approval_description + '</blockquote>';
                                }
                                if (response.approval_file_url) {
                                    fileHtml = '<div class="mb-1"><i class="fas fa-paperclip text-info mr-1"></i><a href="' + response.approval_file_url + '" target="_blank" class="text-info font-weight-bold">' + (response.approval_file_name || 'Download Attachment') + '</a></div>';
                                }
                                if (response.approval_remarks) {
                                    remarksHtml = '<blockquote class="quote-warning" style="margin-bottom: 5px; padding: 8px; background-color: #fff8e6; border-left: 3px solid #ffc107;">' + response.approval_remarks + '</blockquote>';
                                }
                            }

                            $('#accept_description_view').html(descHtml || '<blockquote class="quote-info" style="margin: 0; padding: 8px; background-color: #f8f9fa; border-left: 3px solid #17a2b8;">No description provided.</blockquote>');

                            if (fileHtml) {
                                $('#accept_file_view').html(fileHtml);
                                $('#accept_file_container').show();
                            } else {
                                $('#accept_file_container').hide();
                            }

                            if (remarksHtml) {
                                $('#accept_past_remarks').html(remarksHtml).show();
                            } else {
                                $('#accept_past_remarks').hide();
                            }

                            $('#accept_remarks').val('');
                            $('#accept_approval_modal').modal('show');
                        }
                    }
                });
            });

            // Green button form submission
            $(document).on('click', '.btn-submit-accept', function () {
                var decision = $(this).data('decision');
                $('#accept_decision').val(decision);

                var form = $('#accept_approval_form');
                if (!form[0].checkValidity()) {
                    form[0].reportValidity();
                    return;
                }

                var row_id = form.data('row_id');
                var url = "{{ route('admin.shelf-initiatives.accept-approval', ':id') }}";
                url = url.replace(':id', row_id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function (response) {
                        if (response.success) {
                            $('#accept_approval_modal').modal('hide');
                            window.LaravelDataTables['shelf-initiatives-table'].ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error('Failed to submit approval.');
                        }
                    },
                    error: function (xhr) {
                        toastr.error('An error occurred during submission.');
                    }
                });
            });

            function delete_row(element, row_id) {
                var url = "{{ route('admin.shelf-initiatives.destroy', ':id') }}";
                url = url.replace(':id', row_id);

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: { confirmButton: 'btn btn-success mx-1', cancelButton: 'btn btn-danger' },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: url,
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.success) {
                                    window.LaravelDataTables['shelf-initiatives-table'].ajax.reload();
                                    swalWithBootstrapButtons.fire('Deleted!', 'Shelf Initiative has been deleted.', 'success');
                                }
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
</x-layout>