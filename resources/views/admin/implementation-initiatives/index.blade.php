<x-layout>
    <x-breadcrump title='Implementation Initiatives List' parent='Implementation Initiatives' child='List' index="implementation-initiatives" />

    <div class='card'>
        <div class='card-header'>
            <div class="row align-items-center">
                <div class='col-md-3'>
                    <div class="form-group mb-0">
                        <select id="filter_directorate" class="form-control select2">
                            <option value="">All Directorates</option>
                            @foreach($directorates as $directorate)
                                <option value="{{ $directorate->id }}">{{ $directorate->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class="form-group mb-0">
                        <select id="filter_theme" class="form-control select2">
                            <option value="">All Themes</option>
                            @foreach($themes as $theme)
                                <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class="form-group mb-0">
                        <select id="filter_objective" class="form-control select2">
                            <option value="">All Objectives</option>
                            @foreach($objectives as $objective)
                                <option value="{{ $objective->id }}">{{ $objective->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='col-md-3 text-right'>
                    @can('implementation-initiative: create')
                        <a href="{{ route('admin.implementation-initiatives.create') }}" class="btn btn-primary">
                            Add New Initiative
                        </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class='card-body'>
            {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
        </div>
    </div>

    @php
        $partners = \App\Models\Partner::all();
        $initiativeStatuses = \App\Models\InitiativeStatus::all();
    @endphp

    <x-partials.implementation_initiative_modal :partners="$partners" :initiativeStatuses="$initiativeStatuses" :objectives="$objectives" :directorates="$directorates" :implementationStatuses="$implementationStatuses" :themes="$themes" />
    <x-show-modals.implementation_initiative_show_modal />

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
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
                            success: function(data) {
                                if (data.success) {
                                    window.LaravelDataTables['implementation-initiatives-table'].ajax.reload();
                                    swalWithBootstrapButtons.fire('Deleted!', 'Initiative has been deleted.', 'success');
                                }
                            }
                        });
                    }
                });
            }

            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Implementation Initiative');
            }

            $(document).ready(function() {
                // Initialize filters (not in modal)
                $('.card-header .select2').select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });

                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true
                });

                // Initialize modal selects
                $('#update_modal .select2').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    dropdownParent: $('#update_modal')
                });

                $(document).on('change', '#filter_directorate, #filter_objective', function() {
                    window.LaravelDataTables['implementation-initiatives-table'].ajax.reload();
                });

                // When theme filter changes, reload objective options then reload table
                $(document).on('change', '#filter_theme', function() {
                    var themeId = $(this).val();
                    if (themeId) {
                        $.ajax({
                            url: "{{ route('admin.get-objectives-by-theme') }}",
                            type: "GET",
                            data: { theme_id: themeId },
                            dataType: "json",
                            success: function(data) {
                                $('#filter_objective').empty();
                                $('#filter_objective').append('<option value="">All Objectives</option>');
                                $.each(data, function(key, value) {
                                    $('#filter_objective').append('<option value="' + value.id + '">' + value.name + '</option>');
                                });
                                if ($('#filter_objective').hasClass('select2-hidden-accessible')) {
                                    $('#filter_objective').trigger('change.select2');
                                }
                                window.LaravelDataTables['implementation-initiatives-table'].ajax.reload();
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
                        window.LaravelDataTables['implementation-initiatives-table'].ajax.reload();
                    }
                });

                $(document).on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.implementation-initiatives.edit', ':id') }}";
                    url = url.replace(':id', row_id);
                    $('#implementation_initiative_update_form :input').not(':submit, :button, :hidden').val('');
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                var initiative = response.initiative;
                                $('#initiative_id').val(initiative.id);
                                $('#name').val(initiative.name);
                                $('#directorate_id').val(initiative.directorate_id).trigger('change');
                                $('#implementation_status_id').val(initiative.implementation_status_id).trigger('change');
                                $('#start_date').val(response.start_date);
                                $('#end_date').val(response.end_date);
                                $('#budget').val(initiative.budget);
                                $('#expenditure').val(initiative.expenditure);
                                $('#partner_id').val(initiative.partner_id).trigger('change');
                                $('#completion').val(initiative.completion);
                                $('#initiative_status_id').val(initiative.initiative_status_id).trigger('change');
                                $('#request').val(initiative.request).trigger('change');
                                $('#note').val(initiative.note);

                                var themeId = initiative.theme_id || (initiative.objective ? initiative.objective.theme_id : null);
                                if (themeId) {
                                    $('#theme_id_modal').data('selected-objective', initiative.objective_id);
                                    $('#theme_id_modal').val(themeId).trigger('change');
                                } else {
                                    $('#theme_id_modal').val('').trigger('change');
                                    $('#objective_id_modal').val(initiative.objective_id).trigger('change');
                                }

                                $('#update_modal').modal('show');
                            }
                        }
                    });
                });

                $(document).on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.implementation-initiatives.show', ':id') }}";
                    url = url.replace(':id', row_id);
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                $('#show_modal #name_show').html(response.initiative.name);
                                $('#show_modal #directorate_show').html(response.directorateName);
                                $('#show_modal #theme_show').html(response.themeName);
                                $('#show_modal #objective_show').html(response.objectiveName);
                                $('#show_modal #start_date_show').html(response.initiative.start_date ? response.initiative.start_date.substring(0, 10) : '');
                                $('#show_modal #end_date_show').html(response.initiative.end_date ? response.initiative.end_date.substring(0, 10) : '');
                                $('#show_modal #budget_show').html(response.initiative.budget);
                                $('#show_modal #expenditure_show').html(response.initiative.expenditure);
                                $('#show_modal #partner_show').html(response.partnerName);
                                $('#show_modal #completion_show').html(response.initiative.completion ? response.initiative.completion + '%' : '');
                                $('#show_modal #initiative_status_show').html(response.initiativeStatusName);
                                $('#show_modal #request_show').html(response.initiative.request);
                                $('#show_modal #created_by').html(response.getCreatedBy);
                                $('#show_modal #created_at').html(response.created_at);

                                // Populate Support Requests
                                $('#support_requests_show_table tbody').empty();
                                if (response.supportRequests && response.supportRequests.length > 0) {
                                    response.supportRequests.forEach(function(sr, index) {
                                        let priorityClass = sr.priority == 'H' ? 'danger' : (sr.priority == 'M' ? 'warning' : 'info');
                                        let priorityLabel = sr.priority == 'H' ? 'High' : (sr.priority == 'M' ? 'Medium' : 'Low');
                                        let row = `<tr>
                                            <td>${index + 1}</td>
                                            <td>${sr.partner ? sr.partner.name : 'N/A'}</td>
                                            <td>${sr.activities}</td>
                                            <td>${sr.request_status ? sr.request_status.name : 'N/A'}</td>
                                            <td><span class="badge badge-${priorityClass}">${priorityLabel}</span></td>
                                        </tr>`;
                                        $('#support_requests_show_table tbody').append(row);
                                    });
                                } else {
                                    $('#support_requests_show_table tbody').append('<tr><td colspan="5" class="text-center">No support requests found</td></tr>');
                                }

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
                                window.LaravelDataTables['implementation-initiatives-table'].ajax.reload();
                                toastr.success('You have successfully updated Implementation Details.');
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
