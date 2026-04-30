<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title='Initiative Statuses List' parent='Initiative Statuses' child='List' index="initiative-statuses" />
    <!-- /.content-header -->

    <!-- /.content-Main -->
    <div class='card'>
        <div class='card-header'>
            <div class='col'>
                <div style='display: flex; justify-content:flex-end'>
                    <div>
                        @can('initiative-status: create')
                            <a href="{{ route('admin.initiative-statuses.create') }}">
                                <button type='button' class='btn btn-primary'>Add New Status</button>
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
    <x-partials.initiative_status_modal />
    <x-show-modals.initiative_status_show_modal />
    <!-- /#updateModal -->
    <!-- /.content -->
    <!-- Custom Js contents -->
    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            //delete row
            function delete_row(element, row_id) {
                var url = "{{ route('admin.initiative-statuses.destroy', ':id') }}";
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
                                    window.LaravelDataTables['initiative-statuses-table'].ajax.reload();
                                    swalWithBootstrapButtons.fire(
                                        'Deleted!',
                                        'Status has been deleted.',
                                        'success'
                                    )
                                }
                            },
                            error: function(error) {
                                console.log('Error deleting status');
                            }
                        })
                    }
                })
            }

            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Initiative Status')
            }

            $(document).ready(function() {
                // Update record popup
                $('#initiative-statuses-table').on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.initiative-statuses.edit', ':id') }}";
                    url = url.replace(':id', row_id);

                    $('#initiative_status_update_form :input').val('');
                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            var status = response.initiativeStatus
                            if (response.success == 1) {
                                $('#initiative_status_id').val(status.id);
                                $('#name').val(status.name);
                                $('#update_modal').modal('show');
                            } else {
                                alert('Invalid ID.');
                            }
                        }
                    });
                });

                //show
                $('#initiative-statuses-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.initiative-statuses.show', ':id') }}";
                    url = url.replace(':id', row_id);

                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            var status = response.initiativeStatus
                            if (response.success == 1) {
                                $('#show_modal #name').html(status.name);
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

            $('#initiative_status_update_form').on('submit', function(e) {
                e.preventDefault();
                form_data = $(this).serialize();
                row_id = $('#initiative_status_id', $(this)).val()

                var url = "{{ route('admin.initiative-statuses.update', ':id') }}";
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
                            window.LaravelDataTables['initiative-statuses-table'].ajax.reload();
                            toastr.success('You have successfully updated the Status.')
                        }
                    },
                    error: function(error) {
                        console.log('Error updating status');
                    }
                });
            });
        </script>
    @endpush
</x-layout>
