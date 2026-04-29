<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Custom Exceptions Lists" parent="Custom Exceptions" child="List" />
    <!-- /.content-header -->

    <!-- /.content-Main -->
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="card">
        <div class="card-header">
            <form action="{{ route('delete.all.data') }}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" class="delete-button btn btn-danger">Clear Exceptions</button>
            </form>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
    </div>

    <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <!-- /#updateModal -->
    <x-partials.customException_modal />
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
        //delete Custom Exception
        function delete_exception(element, row_id) {
            var url = "{{ route('admin.custom-exceptions.destroy', ':id') }}";
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
                        type: "DELETE",
                        url: url,
                        data: {
                            row_id: row_id,
                        },
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            if (data.success) {
                                window.LaravelDataTables["customExceptions-table"].ajax.reload();
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
        //fixorNot Custom Exception
        function fixorNot_exception(element, row_id) {
            var url = "{{ route('admin.custom-exceptions.update', ':id') }}";
            url = url.replace(':id', row_id);
            console.log(url);
            // var row_id = raw_id;

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success mx-1',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "Is the Issue resolved",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "PATCH",
                        url: url,
                        data: {
                            row_id: row_id,
                        },
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            if (data.success) {
                                window.LaravelDataTables["customExceptions-table"].ajax.reload();
                            }
                        },
                        error: function(error) {
                            if (error.status ==
                                422) { // when status code is 422, it's a validation issue

                            }
                            console.log('debug error here');
                        }
                    })

                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Issue has been not resolved. :)',
                        'error'
                    )
                }
            })
        }

        $(document).ready(function() {
            // Dispaly Custom Exception Detail in popup modal
            $('#customExceptions-table').on('click', '#update_row', function() {
                var row_id = $(this).data('row_id');
                var url = "{{ route('admin.custom-exceptions.show', ':id') }}";
                url = url.replace(':id', row_id);

                // AJAX request
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: 'json',
                    success: function(response) {
                        console.log('success');
                        if (response.success == 1) {
                            console.log(response);
                            $('#customException_id').html(response.customException_id);
                            $('#description').html(response.description);
                            $('#update_modal').modal('show');

                        } else {
                            alert("Invalid ID.");
                        }
                    }
                });
            });
        });
    </script>

    @endpush
    <!-- Custom Js contents -->
    <style>
        .delete-button {
            float: right;
        }

        code {
            font-size: 20px;
            line-height: 28px;
            background-color: #fff;
            color: red;
        }
    </style>


</x-layout>