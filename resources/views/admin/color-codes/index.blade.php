<x-layout>
        <!-- Content Header (Page header) -->
        <x-breadcrump title='Color Codes List' parent='Color Codes' child='List' />
        <!-- /.content-header -->

        <!-- /.content-Main -->
        <div class='card'>
            <div class='card-header'>
                <div class='col'>
                    <div style='display: flex; justify-content:flex-end'>
                        <div>
                        @can('color-code: create')
                        <a href="{{route('admin.color-codes.create') }}">
                            <button type='button' class='btn btn-primary'>Add New Color Code</button>
                        </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class='card-body'>
                {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
            </div>

            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /#updateModal -->
        <x-partials.color_code_modal />
        <x-show-modals.color_code_show_modal />
        <!-- /#updateModal -->
        <!-- /.content -->
        <!-- Custom Js contents -->
        @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script></script>
        <script>


            //delete row
            function delete_row(element, row_id) {
                var url = "{{ route('admin.color-codes.destroy', ':id') }}";
                url = url.replace(':id', row_id);
                console.log(url);

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mx-1',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

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
                                row_id: row_id,
                            },
                            dataType: 'json',
                            success: function(data) {
                                console.log(data);
                                if (data.success) {
                                    window.LaravelDataTables['color-codes-table'].ajax.reload();
                                }
                            },
                            error: function(error) {
                                if (error.status ==
                                    422) { // when status code is 422, it's a validation issue

                                }
                                console.log('debug error here');
                            }
                        })
                        swalWithBootstrapButtons.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                        )
                    }
                })
            }

            if (@json(session('success_create'))) {

                toastr.success('You have successfuly added a new Color Code')
            }

            $(document).ready(function() {
                // Update record popup
                $('#color-codes-table').on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.color-codes.edit', ':id') }}";
                    url = url.replace(':id', row_id);

                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            console.log('success');
                            var color_code = response.color_code
                            if (response.success == 1) {
                                console.log(color_code);
                                $('#color_code_id').val(color_code.id);
$('#label').val(color_code.label);
$('#min').val(color_code.min);
$('#max').val(color_code.max);
$('#color').val(color_code.color);
 $('#update_modal').modal('show');

                            } else {
                                alert('Invalid ID.');
                            }
                        }
                    });
                });

                //show
                $('#color-codes-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.color-codes.show', ':id') }}";
                    url = url.replace(':id', row_id);

                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            console.log('success');
                            var color_code = response.color_code
                            if (response.success == 1) {
                                console.log(color_code);
                                $('#color_code_id').val(color_code.id);$('#show_modal #label').html(color_code.label);
$('#show_modal #min').html(color_code.min);
$('#show_modal #max').html(color_code.max);
$('#show_modal #color').html(color_code.color);
 $('#show_modal').modal('show');

                            } else {
                                alert('Invalid ID.');
                            }
                        }
                    });
                });
            });


            $('#color_code_update_form').on('submit', function(e) {
                e.preventDefault();
                form_data = $(this).serialize();
                row_id = $('#color_code_id', $(this)).val()
                console.log(row_id);

                var url = "{{ route('admin.color-codes.update', ':id') }}";
                url = url.replace(':id', row_id);

                // AJAX request
                $.ajax({
                    url: url,
                    type: 'PATCH',
                    data: form_data,
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $('#update_modal').modal('toggle');
                            window.LaravelDataTables['color-codes-table'].ajax.reload();
                            toastr.success('You have successfuly updated a Color Code.')
                        }
                    },
                    error: function(error) {
                        console.log('error');
                    }
                });

            });
        </script>
        @endpush
        <!-- Custom Js contents -->

    </x-layout>
