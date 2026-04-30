<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title='Directorates List' parent='Directorates' child='List' index="directorates" />
    <!-- /.content-header -->

    <!-- /.content-Main -->
    <div class='card'>
        <div class='card-header'>
            <div class='col'>
                <div style='display: flex; justify-content:flex-end'>
                    <div>
                        @can('directorate: create')
                            <a href="{{ route('admin.directorates.create') }}">
                                <button type='button' class='btn btn-primary'>Add New Directorate</button>
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
    <x-partials.directorate_modal :users="$users" />
    <x-show-modals.directorate_show_modal />
    <!-- /#updateModal -->
    <!-- /.content -->
    <!-- Custom Js contents -->
    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            //delete row
            function delete_row(element, row_id) {
                var url = "{{ route('admin.directorates.destroy', ':id') }}";
                url = url.replace(':id', row_id);

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
                            dataType: 'json',
                            success: function(data) {
                                if (data.success) {
                                    window.LaravelDataTables['directorates-table'].ajax.reload();
                                    swalWithBootstrapButtons.fire(
                                        'Deleted!',
                                        'Directorate has been deleted.',
                                        'success'
                                    )
                                }
                            },
                            error: function(error) {
                                console.log('Error deleting directorate');
                            }
                        })
                    }
                })
            }

            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Directorate')
            }

            $(document).ready(function() {
                // Update record popup
                $('#directorates-table').on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.directorates.edit', ':id') }}";
                    url = url.replace(':id', row_id);

                    $('#directorate_update_form :input').val('');
                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            var directorate = response.directorate
                            if (response.success == 1) {
                                $('#directorate_id').val(directorate.id);
                                $('#name').val(directorate.name);
                                $('#user_id').val(directorate.user_id).trigger('change');
                                $('#update_modal').modal('show');
                            } else {
                                alert('Invalid ID.');
                            }
                        }
                    });
                });

                //show
                $('#directorates-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.directorates.show', ':id') }}";
                    url = url.replace(':id', row_id);

                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                var directorate = response.directorate;
                                $('#show_modal #name').html(directorate.name);
                                $('#show_modal #director').html(response.director ? response.director.first_name + ' ' + response.director.last_name : 'N/A');
                                $('#show_modal #created_by').html(response.getCreatedBy);
                                $('#show_modal #created_at').html(response.created_at);
                                $('#show_modal').modal('show');
                            } else {
                                alert('Invalid ID.');
                            }
                        }
                    });
                });
            });

            $('#directorate_update_form').on('submit', function(e) {
                e.preventDefault();
                form_data = $(this).serialize();
                row_id = $('#directorate_id', $(this)).val()

                var url = "{{ route('admin.directorates.update', ':id') }}";
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
                            window.LaravelDataTables['directorates-table'].ajax.reload();
                            toastr.success('You have successfully updated the Directorate.')
                        }
                    },
                    error: function(error) {
                        console.log('Error updating directorate');
                    }
                });
            });
        </script>
    @endpush
</x-layout>
