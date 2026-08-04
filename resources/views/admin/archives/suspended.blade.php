<x-layout>
    <x-breadcrump title='Suspended Initiatives List' parent='Archives' child='Suspended Initiatives' index="suspended-initiatives" />

    <div class='card'>
        <div class='card-header'>
            <div class="row align-items-center">
                <div class='col-md-4'>
                    <div class="form-group mb-0">
                        <select id="filter_directorate" class="form-control select2">
                            <option value="">All Directorates</option>
                            @foreach($directorates as $directorate)
                                <option value="{{ $directorate->id }}">{{ $directorate->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='col-md-4'>
                    <div class="form-group mb-0">
                        <select id="filter_theme" class="form-control select2">
                            <option value="">All Themes</option>
                            @foreach($themes as $theme)
                                <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='col-md-4'>
                    <div class="form-group mb-0">
                        <select id="filter_objective" class="form-control select2">
                            <option value="">All Objectives</option>
                            @foreach($objectives as $objective)
                                <option value="{{ $objective->id }}">{{ $objective->name }}</option>
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

    <x-partials.implementation_initiative_modal :objectives="$objectives" :directorates="$directorates" :implementationStatuses="$implementationStatuses" :themes="$themes" :initiativeActivitiesEditTable="$initiativeActivitiesEditTable" />
    <x-partials.activity_modal :partners="$partners" :priorities="$priorities" :initiatives="$initiatives" :activityStatuses="$activityStatuses" :directorates="$directorates" />
    <x-show-modals.implementation_initiative_show_modal :initiativeActivitiesShowTable="$initiativeActivitiesShowTable" />

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        {!! $initiativeActivitiesEditTable->html()->scripts() !!}
        {!! $initiativeActivitiesShowTable->html()->scripts() !!}
        <script>
            // Disable Bootstrap focus enforcement to prevent nested modal focus stealing (Select2)
            $.fn.modal.Constructor.prototype._enforceFocus = function() {};

            function reloadInitiativeActivitiesTable(tableId) {
                if (window.LaravelDataTables && window.LaravelDataTables[tableId]) {
                    window.LaravelDataTables[tableId].ajax.reload(null, false);
                }
            }

            function delete_row(element, row_id) {
                var url = "{{ route('admin.implementation-initiatives.destroy', ':id') }}";
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
                            type: 'DELETE', url: url, dataType: 'json',
                            data: { _token: "{{ csrf_token() }}" },
                            success: function(data) {
                                if (data.success) {
                                    window.LaravelDataTables['suspended-initiatives-table'].ajax.reload();
                                    swalWithBootstrapButtons.fire('Deleted!', 'Initiative has been deleted.', 'success');
                                }
                            }
                        });
                    }
                });
            }

            $(document).ready(function () {
                $('.card-header .select2').select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });

                $('#filter_directorate, #filter_objective').on('change', function () {
                    window.LaravelDataTables['suspended-initiatives-table'].ajax.reload();
                });

                $('#filter_theme').on('change', function () {
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
                                window.LaravelDataTables['suspended-initiatives-table'].ajax.reload();
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
                        window.LaravelDataTables['suspended-initiatives-table'].ajax.reload();
                    }
                });

                $(document).on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.implementation-initiatives.edit', ':id') }}";
                    url = url.replace(':id', row_id);
                    $('#implementation_initiative_update_form :input').not(':submit, :button, :hidden').val('');
                    $('#directorates').val([]).trigger('change');
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                var initiative = response.initiative;
                                $('#initiative_id').val(initiative.id);
                                $('#name').val(initiative.name);
                                $('#directorates').val(response.directorates).trigger('change');
                                $('#implementation_status_id').val(initiative.implementation_status_id).trigger('change');
                                $('#note').val(initiative.note);

                                var archivalType = parseInt(initiative.archival_type) || 0;
                                $('#archival_type').val(archivalType);
                                $('#archival_completed').prop('checked', archivalType === 1);
                                $('#archival_pending').prop('checked', archivalType === 2);

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

                $('#suspended-initiatives-table').on('click', '#show_row', function () {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.implementation-initiatives.show', ':id') }}";
                    url = url.replace(':id', row_id);
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function (response) {
                            if (response.success == 1) {
                                $('#show_modal #name_show').html(response.initiative.name);
                                $('#show_modal #directorate_show').html(response.directorateName);
                                $('#show_modal #theme_show').html(response.themeName);
                                $('#show_modal #objective_show').html(response.objectiveName);
                                $('#show_modal #note_show').html(response.initiative.note || '');

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
                                    $.each(response.histories, function (index, h) {
                                        if (!cycleMap[h.cycle_number]) {
                                            cycleMap[h.cycle_number] = { cycle_number: h.cycle_number, action: h.action, description: h.description, file_url: h.file_url, file_name: h.file_name, remarks: h.remarks, created_at: h.created_at, user_name: h.user_name };
                                        } else {
                                            if (h.action) cycleMap[h.cycle_number].action = h.action;
                                            if (h.description) cycleMap[h.cycle_number].description = h.description;
                                            if (h.file_url) { cycleMap[h.cycle_number].file_url = h.file_url; cycleMap[h.cycle_number].file_name = h.file_name; }
                                            if (h.remarks) cycleMap[h.cycle_number].remarks = h.remarks;
                                            if (h.created_at) cycleMap[h.cycle_number].created_at = h.created_at;
                                            if (h.user_name) cycleMap[h.cycle_number].user_name = h.user_name;
                                        }
                                    });
                                    $.each(cycleMap, function (cycleNum, h) {
                                        var actionBadge = '';
                                        if (h.action === 'requested') actionBadge = '<span class="badge badge-warning">Requested</span>';
                                        else if (h.action === 'rejected') actionBadge = '<span class="badge badge-danger">Rejected</span>';
                                        else if (h.action === 'approved') actionBadge = '<span class="badge badge-success">Approved</span>';
                                        else actionBadge = '<span class="badge badge-secondary">' + h.action + '</span>';

                                        var descText = h.description || 'N/A';
                                        if (h.user_name) {
                                            descText = '<div class="small text-muted font-weight-bold mb-1"><i class="fas fa-user mr-1"></i>' + h.user_name + '</div>' + descText;
                                        }

                                        var fileLink = h.file_url ? '<a href="' + h.file_url + '" target="_blank" class="text-info font-weight-bold"><i class="fas fa-paperclip mr-1"></i>' + (h.file_name || 'Download') + '</a>' : '-';
                                        tbodyHtml += '<tr>' +
                                            '<td class="text-center font-weight-bold">Cycle #' + h.cycle_number + '</td>' +
                                            '<td>' + actionBadge + '</td>' +
                                            '<td>' + descText + '</td>' +
                                            '<td>' + fileLink + '</td>' +
                                            '<td>' + (h.remarks || 'N/A') + '</td>' +
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

                $('#implementation_initiative_update_form').on('submit', function(e) {
                    e.preventDefault();
                    var form_data = $(this).serialize();
                    var row_id = $('#initiative_id', $(this)).val();
                    var url = "{{ route('admin.implementation-initiatives.update', ':id') }}";
                    url = url.replace(':id', row_id);
                    $.ajax({
                        url: url, type: 'PATCH', data: form_data, dataType: 'json',
                        success: function(data) {
                            if (data.success) {
                                $('#update_modal').modal('toggle');
                                window.LaravelDataTables['suspended-initiatives-table'].ajax.reload();
                                toastr.success('You have successfully updated Suspended Initiative Details.');
                            }
                        },
                        error: function(xhr) {
                            if(xhr.responseJSON && xhr.responseJSON.errors) {
                                let errors = xhr.responseJSON.errors;
                                let errorHtml = '<ul>';
                                $.each(errors, function(key, value) {
                                    errorHtml += '<li>' + value[0] + '</li>';
                                });
                                errorHtml += '</ul>';
                                toastr.error(errorHtml, 'Validation Error');
                            } else {
                                toastr.error('An error occurred.', 'Error');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layout>
