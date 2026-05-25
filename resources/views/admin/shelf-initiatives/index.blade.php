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
            </div>
        </div>
        <div class='card-body'>
            {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
        </div>
    </div>

    <x-partials.shelf_initiative_modal :objectives="$objectives" :directorates="$directorates" :implementationStatuses="$implementationStatuses" :themes="$themes" :initiativeActivitiesEditTable="$initiativeActivitiesEditTable" />
    <x-partials.activity_modal :partners="$partners" :requestStatuses="$requestStatuses" :priorities="$priorities" :initiatives="$initiatives" :activityStatuses="$activityStatuses" :directorates="$directorates" />
    <x-show-modals.shelf_initiative_show_modal :initiativeActivitiesShowTable="$initiativeActivitiesShowTable" />

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        {!! $initiativeActivitiesEditTable->html()->scripts() !!}
        {!! $initiativeActivitiesShowTable->html()->scripts() !!}
        <script>
            function reloadInitiativeActivitiesTable(tableId) {
                if (window.LaravelDataTables && window.LaravelDataTables[tableId]) {
                    window.LaravelDataTables[tableId].ajax.reload(null, false);
                }
            }
            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Shelf Initiative');
            }

            $(document).ready(function() {
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


                $('#activity_modal .select2').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    dropdownParent: $('#activity_modal')
                });
            });

            // Fix Bootstrap stacked modal scroll: restore body state when inner modal closes
            $('#activity_modal').on('hidden.bs.modal', function() {
                if ($('#update_modal').hasClass('show')) {
                    $('body').addClass('modal-open');
                }
            });

            $('#update_modal').on('shown.bs.modal', function () {

    $(this).find('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        dropdownParent: $('#update_modal')
    });

});



            $(document).on('click', '#add_activity', function() {
                var currentInitiativeId = $('#initiative_id').val();
                $('#activity_form')[0].reset();
                $('#sr_partner_id').val('').trigger('change');
                $('#sr_interested_partners').val([]).trigger('change');
                $('#sr_directorates').val([]).trigger('change');
                $('#sr_request_status_id').val('').trigger('change');
                $('#sr_activity_status_id').val('').trigger('change');
                $('#sr_request_type').val('').trigger('change');
                $('#sr_priority').val('').trigger('change');
                $('#support_modal_title').text('Add Activity Request');
                $('#activity_form').attr('action', "{{ route('admin.activities.store') }}");
                $('#support_method').val('POST');

                // Pre-select the current initiative and disable the field
                // Use setTimeout to ensure Select2 renders after the form reset cycle
                setTimeout(function() {
                    $('#sr_initiative_id').val(currentInitiativeId).trigger('change');
                    $('#sr_initiative_id').prop('disabled', true);
                }, 50);

                $('#activity_modal').modal('show');
            });

            $(document).on('change', '#filter_directorate, #filter_objective', function() {
                window.LaravelDataTables['shelf-initiatives-table'].ajax.reload();
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

            $(document).on('click', '#update_row', function() {
                var row_id = $(this).data('row_id');
                var url = "{{ route('admin.shelf-initiatives.edit', ':id') }}";
                url = url.replace(':id', row_id);
                $('#shelf_initiative_update_form :input').not(':submit, :button, :hidden').val('');
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

            $(document).on('click', '#show_row', function() {
                var row_id = $(this).data('row_id');
                var url = "{{ route('admin.shelf-initiatives.show', ':id') }}";
                url = url.replace(':id', row_id);
                $.ajax({
                    url: url, type: 'GET', dataType: 'json',
                    success: function(response) {
                        if (response.success == 1) {
                            $('#show_modal #name_show').html(response.initiative.name);
                            $('#show_modal #directorate_show').html(response.directorateName);
                            $('#show_modal #theme_show').html(response.themeName);
                            $('#show_modal #objective_show').html(response.objectiveName);
                            $('#show_modal #note_show').html(response.initiative.note ?? '');

                            $('#show_initiative_id').val(row_id);
                            reloadInitiativeActivitiesTable('initiative-activities-show-table');

                            $('#show_modal').modal('show');
                        }
                    }
                });
            });

            $(document).on('submit', '#shelf_initiative_update_form', function(e) {
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
                let initiative_id = $(this).data('initiative_id');
                var url = "/admin/activities/" + id + "/edit";

                $('#support_modal_title').text('Edit Activity Request');
                $('#activity_form').attr('action', '/admin/activities/' + id);
                $('#support_method').val('PATCH');

                $.ajax({
                    url: url, type: 'GET', dataType: 'json',
                    success: function(response) {
                        if (response.success == 1) {
                            $('#sr_initiative_id').val(initiative_id).trigger('change').attr('disabled', true);
                            $('#sr_partner_id').val(response.activity.partner_id).trigger('change');
                            $('#sr_interested_partners').val(response.interested_partners).trigger('change');
                            $('#sr_directorates').val(response.directorates).trigger('change');
                            $('#sr_activities').val(response.activity.activities);
                            $('#sr_request_status_id').val(response.activity.request_status_id ? response.activity.request_status_id.toString() : '').trigger('change.select2');
                            $('#sr_priority').val(response.activity.priority).trigger('change.select2');
                            $('#sr_start_date').val(response.activity.start_date ? response.activity.start_date.substring(0, 10) : '');
                            $('#sr_end_date').val(response.activity.end_date ? response.activity.end_date.substring(0, 10) : '');
                            $('#sr_budget').val(response.activity.budget);
                            $('#sr_completion').val(response.activity.completion);
                            $('#sr_activity_status_id').val(response.activity.activity_status_id ? response.activity.activity_status_id.toString() : '').trigger('change.select2');
                            $('#sr_request_type').val(response.activity.request_type).trigger('change.select2');
                            $('#sr_expenditure').val(response.activity.expenditure);
                            $('#activity_modal').modal('show');
                        }
                    }
                });
            });

            $(document).on('submit', '#activity_form', function(e) {
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
                    success: function(response) {
                        if (response.success) {
                            $('#activity_modal').modal('hide');
                            toastr.success('Activity saved successfully');
                            reloadInitiativeActivitiesTable('initiative-activities-edit-table');
                        }
                    }
                });
            });

            $(document).on('click', '.delete-sr', function() {
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
                            success: function(data) {
                                if (data.success) {
                                    swalWithBootstrapButtons.fire('Deleted!', 'Activity has been deleted.', 'success');
                                    reloadInitiativeActivitiesTable('initiative-activities-edit-table');
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
</x-layout>
