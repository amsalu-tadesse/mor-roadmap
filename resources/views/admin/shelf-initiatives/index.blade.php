<x-layout>
    <x-breadcrump title='Shelf Initiatives' parent='Shelf Initiatives' child='List' index="shelf-initiatives" />

    <div class='card'>
        <div class='card-header'>
            <div class="row">
                <div class='col-md-4'>
                    <div class="form-group">
                        <select id="filter_directorate" class="form-control select2">
                            <option value="">All Directorates</option>
                            @foreach($directorates as $directorate)
                                <option value="{{ $directorate->id }}">{{ $directorate->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='col-md-4'>
                    <div class="form-group">
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

    <x-partials.shelf_initiative_modal :objectives="$objectives" :directorates="$directorates" :implementationStatuses="$implementationStatuses" />
    <x-partials.support_request_modal :partners="$partners" :requestStatuses="$requestStatuses" :priorities="$priorities" :initiatives="[]" />
    <x-show-modals.shelf_initiative_show_modal />

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Shelf Initiative');
            }

            $(document).ready(function() {
                $('.select2').select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });

                $('#filter_directorate, #filter_objective').on('change', function() {
                    window.LaravelDataTables['shelf-initiatives-table'].ajax.reload();
                });

                $('#shelf-initiatives-table').on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.shelf-initiatives.edit', ':id') }}";
                    url = url.replace(':id', row_id);
                    $('#shelf_initiative_update_form :input').not(':submit, :button, :hidden').val('');
                    $('#shelf-support-requests-table tbody').empty();
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                $('#initiative_id').val(response.initiative.id);
                                $('#name').val(response.initiative.name);
                                $('#objective_id').val(response.initiative.objective_id);
                                $('#directorate_id').val(response.initiative.directorate_id);
                                $('#implementation_status_id').val(response.initiative.implementation_status_id);
                                $('#note').val(response.initiative.note);

                                // Populate Support Requests
                                if ($.fn.DataTable.isDataTable('#shelf-support-requests-table')) {
                                    $('#shelf-support-requests-table').DataTable().destroy();
                                }
                                $('#shelf-support-requests-table tbody').empty();

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
                                            <td>
                                                <button type="button" class="btn btn-sm edit-sr" data-id="${sr.id}" data-partner_id="${sr.partner_id}" data-activities="${sr.activities}" data-status_id="${sr.request_status_id}" data-priority="${sr.priority}">
                                                    <i class="far fa-edit text-info"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm delete-sr" data-id="${sr.id}">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                            </td>
                                        </tr>`;
                                        $('#shelf-support-requests-table tbody').append(row);
                                    });
                                }

                                $('#shelf-support-requests-table').DataTable({
                                    "responsive": true,
                                    "lengthChange": true,
                                    "autoWidth": false,
                                    "buttons": ["csv", "excel", "pdf", "print", "colvis"]
                                }).buttons().container().appendTo('#shelf-support-requests-table_wrapper .col-md-6:eq(0)');

                                $('#update_modal').modal('show');
                            }
                        }
                    });
                });

                $('#shelf-initiatives-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.shelf-initiatives.show', ':id') }}";
                    url = url.replace(':id', row_id);
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                $('#show_modal #name_show').html(response.initiative.name);
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
                                $('#show_modal').modal('show');
                            }
                        }
                    });
                });
            });

            $('#shelf_initiative_update_form').on('submit', function(e) {
                e.preventDefault();
                var form_data = $(this).serialize();
                var row_id = $('#initiative_id', $(this)).val();
                var url = "{{ route('admin.shelf-initiatives.update', ':id') }}";
                url = url.replace(':id', row_id);
                $.ajax({
                    url: url, type: 'PATCH', data: form_data, dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $('#update_modal').modal('toggle');
                            window.LaravelDataTables['shelf-initiatives-table'].ajax.reload();
                            toastr.success('You have successfully updated the Shelf Initiative.');
                        }
                    }
                });
            });

            $(document).on('click', '.edit-sr', function() {
                let id = $(this).data('id');
                let partner_id = $(this).data('partner_id');
                let activities = $(this).data('activities');
                let status_id = $(this).data('status_id');
                let priority = $(this).data('priority');

                $('#support_modal_title').text('Edit Support Request');
                $('#support_request_form').attr('action', '/admin/support-requests/' + id);
                $('#support_method').val('PATCH');
                $('#sr_partner_id').val(partner_id);
                $('#sr_activities').val(activities);
                $('#sr_status_id').val(status_id);
                $('#sr_priority').val(priority);
                $('#support_request_modal').modal('show');
            });

            $('#support_request_form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var method = $('#support_method').val();
                var data = form.serialize();

                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            $('#support_request_modal').modal('hide');
                            toastr.success('Support Request saved successfully');
                            // Reload shelf modal data
                            $('#shelf-initiatives-table #update_row[data-row_id="' + $('#initiative_id').val() + '"]').click();
                        }
                    }
                });
            });

            $(document).on('click', '.delete-sr', function() {
                if (confirm('Are you sure you want to delete this support request?')) {
                    let id = $(this).data('id');
                    let url = "/admin/support-requests/" + id;
                    let btn = $(this);
                    $.ajax({
                        url: url, type: 'DELETE', data: { _token: "{{ csrf_token() }}" }, dataType: 'json',
                        success: function(data) {
                            if (data.success) {
                                toastr.success('Support request deleted.');
                                // Reload shelf modal data
                                $('#shelf-initiatives-table #update_row[data-row_id="' + $('#initiative_id').val() + '"]').click();
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-layout>
