<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Configurations" parent="Settings" child="List" />
    <!-- /.content-header -->

    <!-- /.content-Main -->

    <!-- /.card-header -->
    <div class="card-body">
        {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
    </div>

    <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <!-- /#updateModal -->
    <x-partials.setting_modal />
    <!-- /#updateModal -->
    <!-- /.content -->
    <!-- Custom Js contents -->
    @push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
    </script>
    <script>
        $(document).ready(function() {
            // Update record popup
            $('#settings-table').on('click', '#update_setting', function() {
                var row_id = $(this).data('row_id');
                var url = "{{ route('admin.settings.edit', ':id') }}";
                url = url.replace(':id', row_id);

                // AJAX request
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: 'json',
                    success: function(response) {
                        console.log('success');

                        // if (response.success == 1) {
                        //     console.log(response);
                        //     $('#setting_id').val(response.setting_id);
                        //     $('#code').val(response.code);
                        //     $('#name').val(response.name);
                        //     $('#value1').val(response.value1);
                        //     $('#type').val(response.type);

                        //     if (response.value1) {
                        //         $("input[data-bootstrap-switch]").each(function() {
                        //             $(this).bootstrapSwitch('state', true);
                        //         });
                        //     } else {
                        //         $("input[data-bootstrap-switch]").each(function() {
                        //             $(this).bootstrapSwitch('state', false);
                        //         });
                        //     }
                        //     // $('#checkboxvalue1').val(response.value1);
                        //     $('#value2').val(response.value2);
                        //     $('#update_modal').modal('show');

                        // }

                        if (response.success == 1) {
                            console.log(response);
                            $('#setting_id').val(response.setting_id);
                            $('#code').val(response.code);
                            $('#name').val(response.name);
                            $('#value1').val(response.value1);
                            $('#type').val(response.type);

                            // Toggle visibility based on response.type
                            if (response.type === 1) {
                                $('#value1InputhGroup').show();
                                $('#value1SwitchGroup').hide();
                                $('#value2TextareaGroup').hide();
                            } else if (response.type === 2) {
                                $('#value1InputhGroup').hide();
                                $('#value1SwitchGroup').hide();
                                $('#value2TextareaGroup').show();
                            } else if (response.type === 0) {
                                $('#value1InputhGroup').hide();
                                $('#value1SwitchGroup').show();
                                $('#value2TextareaGroup').hide();
                            } else {
                                $('#value1InputhGroup').hide();
                                $('#value1SwitchGroup').hide();
                                $('#value2TextareaGroup').hide();
                            }
                            if (response.value1==1) {
                                $("input[data-bootstrap-switch]").each(function() {
                                    $(this).bootstrapSwitch('state', true);
                                });
                            } else {
                                $("input[data-bootstrap-switch]").each(function() {
                                    $(this).bootstrapSwitch('state', false);
                                });
                            }

                            $('#value2').val(response.value2);
                            $('#update_modal').modal('show');
                        } else {
                            alert("Invalid ID.");
                        }
                    }
                });
            });
        });

        $('#setting_update_form').on('submit', function(e) {
            e.preventDefault();
            form_data = $(this).serialize();
            row_id = $('#setting_id', $(this)).val()
            console.log(row_id);

            // var row_id = form_data['row_id']
            var url = "{{ route('admin.settings.update', ':id') }}";
            url = url.replace(':id', row_id);

            // AJAX request
            $.ajax({
                url: url,
                type: "PATCH",
                data: form_data,
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        console.log(data);
                        $('#update_modal').modal('toggle');
                        window.LaravelDataTables["settings-table"].ajax.reload();
                        toastr.success('You have successfuly updated a Setting.')
                    }
                },
                error: function(error) {
                    console.log('error');
                }
            });

        });
    </script>

    @endpush

</x-layout>
