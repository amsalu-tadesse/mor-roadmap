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

    <x-show-modals.implementation_initiative_show_modal :initiativeActivitiesShowTable="$initiativeActivitiesShowTable" />

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        {!! $initiativeActivitiesShowTable->html()->scripts() !!}
        <script>
            function reloadInitiativeActivitiesTable(tableId) {
                if (window.LaravelDataTables && window.LaravelDataTables[tableId]) {
                    window.LaravelDataTables[tableId].ajax.reload(null, false);
                }
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

                                // Approval status
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

                                // Cycle history
                                if (response.histories && response.histories.length > 0) {
                                    var tbodyHtml = '';
                                    var cycleMap = {};
                                    $.each(response.histories, function (index, h) {
                                        if (!cycleMap[h.cycle_number]) {
                                            cycleMap[h.cycle_number] = { cycle_number: h.cycle_number, action: h.action, description: h.description, file_url: h.file_url, file_name: h.file_name, remarks: h.remarks, created_at: h.created_at };
                                        } else {
                                            if (h.action) cycleMap[h.cycle_number].action = h.action;
                                            if (h.description) cycleMap[h.cycle_number].description = h.description;
                                            if (h.file_url) { cycleMap[h.cycle_number].file_url = h.file_url; cycleMap[h.cycle_number].file_name = h.file_name; }
                                            if (h.remarks) cycleMap[h.cycle_number].remarks = h.remarks;
                                            if (h.created_at) cycleMap[h.cycle_number].created_at = h.created_at;
                                        }
                                    });
                                    $.each(cycleMap, function (cycleNum, h) {
                                        var actionBadge = '';
                                        if (h.action === 'requested') actionBadge = '<span class="badge badge-warning">Requested</span>';
                                        else if (h.action === 'rejected') actionBadge = '<span class="badge badge-danger">Rejected</span>';
                                        else if (h.action === 'approved') actionBadge = '<span class="badge badge-success">Approved</span>';
                                        else actionBadge = '<span class="badge badge-secondary">' + h.action + '</span>';

                                        var fileLink = h.file_url ? '<a href="' + h.file_url + '" target="_blank" class="text-info font-weight-bold"><i class="fas fa-paperclip mr-1"></i>' + (h.file_name || 'Download') + '</a>' : '-';
                                        tbodyHtml += '<tr>' +
                                            '<td class="text-center font-weight-bold">Cycle #' + h.cycle_number + '</td>' +
                                            '<td>' + actionBadge + '</td>' +
                                            '<td>' + (h.description || 'N/A') + '</td>' +
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

            });
        </script>
    @endpush
</x-layout>
