<x-layout>
    <x-breadcrump title='Completed Initiatives List' parent='Archives' child='Completed Initiatives' index="completed-initiatives" />

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
                    window.LaravelDataTables['completed-initiatives-table'].ajax.reload();
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
                                window.LaravelDataTables['completed-initiatives-table'].ajax.reload();
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
                        window.LaravelDataTables['completed-initiatives-table'].ajax.reload();
                    }
                });

                $('#completed-initiatives-table').on('click', '#show_row', function () {
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
