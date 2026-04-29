<x-layout>

    <!-- Content Header (Page header) -->
    <x-breadcrump title="Emails List" parent="Emails" child="List" />
    <!-- /.content-header -->

    <!-- /.content-Main -->
    <div class="card">
        <div class="card-header">
            <div class="col">
                <div style="display: flex; justify-content:flex-end">
                    {{-- <div>
                        <a href="{{route('admin.emails.create') }}">
                    <button type="button" class="btn btn-primary">Add New Email</button>
                    </a>
                </div> --}}
            </div>
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
    <x-partials.email_modal />
    <!-- /#updateModal -->
    <!-- /.content -->
    <!-- Custom Js contents -->
    @push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        // Initialize Bootstrap Switch elements
        $("input[data-bootstrap-switch]").bootstrapSwitch();

        // // Handle toggle switch changes
        // $("input[data-bootstrap-switch]").on('switchChange.bootstrapSwitch', function(event, state) {
        //     var email_id = $(this).data('email_id');
        //     toggleMailing(this, email_id, state); // Pass 'state' as the 'enable' argument
        // });


        //Initialize Select2 Elements
        $('.role_select2').select2();
    </script>

    <script>
        //delete user
        function delete_row(element, row_id) {
            var url = "{{ route('admin.emails.destroy', ':id') }}";
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
                                window.LaravelDataTables["emails-table"].ajax.reload();
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

        //enable/disable mailing
        function sendToggleRequest(element) {
            var email_id = $(element).data('email-id');
            var enable = element.checked; // Get the current state of the switch

            var url = "{{ route('admin.emails.update', ':id') }}";
            url = url.replace(':id', email_id);

            $.ajax({
                type: "PATCH",
                url: url,
                data: {
                    email_id: email_id,
                    enable: enable
                },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    if (data.success) {
                        // No need to toggle the switch class here
                        window.LaravelDataTables["emails-table"].ajax.reload();
                    }
                },
                error: function(error) {
                    if (error.status == 422) {
                        // Handle validation issue
                    }
                    console.log('debug error here');
                }
            });
        }

        if (@json(session('success_create'))) {

            toastr.success('You have successfuly added a new Email.')
        }
        $(document).ready(function() {
            // Update record popup
            $('#emails-table').on('click', '#update_row', function() {
                var row_id = $(this).data('row_id');
                var url = "{{ route('admin.emails.edit', ':id') }}";
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
                            $('#email_id').val(response.email_id);
                            $('#subject').val(response.subject);
                            $('#body').val(response.body);
                            $('#update_modal').modal('show');

                        } else {
                            alert("Invalid ID.");
                        }
                    }
                });
            });
        });


        $('#email_update_form').on('submit', function(e) {
            e.preventDefault();
            form_data = $(this).serialize();
            row_id = $('#email_id', $(this)).val()
            console.log(row_id);

            // var row_id = form_data['row_id']
            var url = "{{ route('admin.emails.update', ':id') }}";
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
                        window.LaravelDataTables["emails-table"].ajax.reload();
                        toastr.success('You have successfuly updated a  Email.')
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