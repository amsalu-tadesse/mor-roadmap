<x-layout>
        <!-- Content Header (Page header) -->
        <x-breadcrump title='Login Attempts List' parent='Login Attempts' child='List' />
        <!-- /.content-header -->
    
        <!-- /.content-Main -->
        <div class='card'>
            <div class='card-header'>
                <div class='col'>
                    <div style='display: flex; justify-content:flex-end'>
                        <div>
                        @can('login-attemptt: create')
                        <a href="{{route('admin.login-attempts.create') }}">
                            <button type='button' class='btn btn-primary'>Add New Login Attempt</button>
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
        <x-partials.login_attempt_modal:users="$users" />
        <x-show-modals.login_attempt_show_modal />
        <!-- /#updateModal -->
        <!-- /.content -->
        <!-- Custom Js contents -->
        @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>$('.users_select2').select2();</script>
        <script>


            //delete row
            function delete_row(element, row_id) {
                var url = "{{ route('admin.login-attempts.destroy', ':id') }}";
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
                                    window.LaravelDataTables['login-attempts-table'].ajax.reload();
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
    
                toastr.success('You have successfuly added a new Login Attempt')
            }
    
            $(document).ready(function() {
                // Update record popup
                $('#login-attempts-table').on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.login-attempts.edit', ':id') }}";
                    url = url.replace(':id', row_id);
    
                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            console.log('success');
                            var login_attempt = response.login_attempt
                            if (response.success == 1) {
                                console.log(login_attempt);
                                $('#login_attempt_id').val(login_attempt.id);
$('#ip_address').val(login_attempt.ip_address);
$('#proxy').val(login_attempt.proxy);
$('#login_time').val(login_attempt.login_time);
$('#logout_time').val(login_attempt.logout_time);
$('#user_id').val(login_attempt.user_id);
 $('#update_modal').modal('show');
    
                            } else {
                                alert('Invalid ID.');
                            }
                        }
                    });
                });

                //show
                $('#login-attempts-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.login-attempts.show', ':id') }}";
                    url = url.replace(':id', row_id);
    
                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            console.log('success');
                            var login_attempt = response.login_attempt
                            if (response.success == 1) {
                                console.log(login_attempt);
                                $('#login_attempt_id').val(login_attempt.id);$('#show_modal #ip_address').html(login_attempt.ip_address);
$('#show_modal #proxy').html(login_attempt.proxy);
$('#show_modal #login_time').html(login_attempt.login_time);
$('#show_modal #logout_time').html(login_attempt.logout_time);
$('#show_modal #user_id').html(login_attempt.user_id);
 $('#show_modal').modal('show');
    
                            } else {
                                alert('Invalid ID.');
                            }
                        }
                    });
                });
            });
    
    
            $('#login_attempt_update_form').on('submit', function(e) {
                e.preventDefault();
                form_data = $(this).serialize();
                row_id = $('#login_attempt_id', $(this)).val()
                console.log(row_id);

                var url = "{{ route('admin.login-attempts.update', ':id') }}";
                url = url.replace(':id', row_id);
    
                // AJAX request
                $.ajax({
                    url: url,
                    type: 'PATCH',
                    data: form_data,
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            console.log('111111111111111');
                            console.log(data);
                            console.log('2222222222222222');
                            $('#update_modal').modal('toggle');
                            window.LaravelDataTables['login-attempts-table'].ajax.reload();
                            toastr.success('You have successfuly updated a Login Attempt.')
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
    