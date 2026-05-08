<x-layout>
    <x-breadcrump title='Draft Initiatives List' parent='Draft Initiatives' child='List' index="draft-initiatives" />

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
                    <div style='display: flex; justify-content:flex-end; align-items: flex-end; height: 100%;'>
                        @can('draft-initiative: create')
                            <a href="{{ route('admin.draft-initiatives.create') }}">
                                <button type='button' class='btn btn-primary'>Draft New Initiative</button>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class='card-body'>
            {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
        </div>
    </div>


    <x-partials.draft_initiative_modal :objectives="$objectives" :directorates="$directorates"
        :implementationStatuses="$implementationStatuses" :themes="$themes" />
    <x-show-modals.draft_initiative_show_modal />

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            function delete_row(element, row_id) {
                var url = "{{ route('admin.draft-initiatives.destroy', ':id') }}";
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
                            success: function (data) {
                                if (data.success) {
                                    window.LaravelDataTables['draft-initiatives-table'].ajax.reload();
                                    swalWithBootstrapButtons.fire('Deleted!', 'Draft Initiative has been deleted.', 'success');
                                }
                            }
                        });
                    }
                });
            }

            if (@json(session('success_create'))) {
                toastr.success('You have successfully drafted a new Initiative');
            }

            $(document).ready(function () {
                $('.select2').select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });

                $('#filter_directorate, #filter_objective').on('change', function () {
                    window.LaravelDataTables['draft-initiatives-table'].ajax.reload();
                });

                // When theme filter changes, reload objectives filter options then reload table
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
                                window.LaravelDataTables['draft-initiatives-table'].ajax.reload();
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
                        window.LaravelDataTables['draft-initiatives-table'].ajax.reload();
                    }
                });

                $('#draft-initiatives-table').on('click', '#update_row', function () {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.draft-initiatives.edit', ':id') }}";
                    url = url.replace(':id', row_id);
                    $('#draft_initiative_update_form :input').not(':submit, :button, :hidden').val('');
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function (response) {
                            if (response.success == 1) {
                                var initiative = response.initiative;
                                $('#initiative_id').val(initiative.id);
                                $('#name').val(initiative.name);
                                $('#directorate_id').val(initiative.directorate_id);
                                $('#implementation_status_id').val(initiative.implementation_status_id);
                                $('#note').val(initiative.note);

                                // Load objectives for the selected theme, then set values
                                var themeId = initiative.theme_id;
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
                                            $('#theme_id_modal').val(themeId);
                                            $('#objective_id_modal').val(initiative.objective_id);
                                        }
                                    });
                                } else {
                                    $('#theme_id_modal').val('');
                                    $('#objective_id_modal').val(initiative.objective_id);
                                }

                                $('#update_modal').modal('show');
                            }
                        }
                    });
                });

                $('#draft-initiatives-table').on('click', '#show_row', function () {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.draft-initiatives.show', ':id') }}";
                    url = url.replace(':id', row_id);
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function (response) {
                            if (response.success == 1) {
                                $('#show_modal #name_show').html(response.initiative.name);
                                $('#show_modal #directorate_show').html(response.directorateName);
                                $('#show_modal #theme_show').html(response.themeName);
                                $('#show_modal #objective_show').html(response.objectiveName);
                                $('#show_modal #implementation_status_show').html(response.implementationStatusName);
                                $('#show_modal #note_show').html(response.initiative.note);
                                $('#show_modal #created_by').html(response.getCreatedBy);
                                $('#show_modal #created_at').html(response.created_at);
                                $('#show_modal').modal('show');
                            }
                        }
                    });
                });
            });

            $('#draft_initiative_update_form').on('submit', function (e) {
                e.preventDefault();
                var form_data = $(this).serialize();
                var row_id = $('#initiative_id', $(this)).val();
                var url = "{{ route('admin.draft-initiatives.update', ':id') }}";
                url = url.replace(':id', row_id);
                $.ajax({
                    url: url, type: 'PATCH', data: form_data, dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            $('#update_modal').modal('toggle');
                            window.LaravelDataTables['draft-initiatives-table'].ajax.reload();
                            toastr.success('You have successfully updated the Draft Initiative.');
                        }
                    }
                });
            });
        </script>
    @endpush
</x-layout>