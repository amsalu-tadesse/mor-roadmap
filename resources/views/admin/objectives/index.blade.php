<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title='Objectives List' parent='Objectives' child='List' index="objectives" />
    <!-- /.content-header -->

    <!-- /.content-Main -->
    <div class='card'>
        <div class='card-header'>
            <div class='col'>
                <div style='display: flex; justify-content:flex-end'>
                    <div>
                        @can('objective: create')
                            <a href="{{ route('admin.objectives.create') }}">
                                <button type='button' class='btn btn-primary'>Add New Objective</button>
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
    <x-partials.objective_modal :themes="$themes" />
    <x-show-modals.objective_show_modal />
    <!-- /#updateModal -->
    <!-- /.content -->
    <!-- Custom Js contents -->
    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            //delete row
            function delete_row(element, row_id) {
                var url = "{{ route('admin.objectives.destroy', ':id') }}";
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
                                    window.LaravelDataTables['objectives-table'].ajax.reload();
                                    swalWithBootstrapButtons.fire(
                                        'Deleted!',
                                        'Objective has been deleted.',
                                        'success'
                                    )
                                }
                            },
                            error: function(error) {
                                console.log('Error deleting objective');
                            }
                        })
                    }
                })
            }

            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Objective')
            }

            $(document).ready(function() {
                // Update record popup
                $('#objectives-table').on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.objectives.edit', ':id') }}";
                    url = url.replace(':id', row_id);

                    $('#objective_update_form :input').val('');
                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            var objective = response.objective
                            if (response.success == 1) {
                                $('#objective_id').val(objective.id);
                                $('#name').val(objective.name);
                                $('#theme_id').val(objective.theme_id);
                                $('#update_modal').modal('show');
                            } else {
                                alert('Invalid ID.');
                            }
                        }
                    });
                });

                //show
                $('#objectives-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.objectives.show', ':id') }}";
                    url = url.replace(':id', row_id);

                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            var objective = response.objective
                            if (response.success == 1) {
                                $('#show_modal #name').html(objective.name);
                                $('#show_modal #theme').html(response.themeName);
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

            $('#objective_update_form').on('submit', function(e) {
                e.preventDefault();
                form_data = $(this).serialize();
                row_id = $('#objective_id', $(this)).val()

                var url = "{{ route('admin.objectives.update', ':id') }}";
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
                            window.LaravelDataTables['objectives-table'].ajax.reload();
                            toastr.success('You have successfully updated the Objective.')
                        }
                    },
                    error: function(error) {
                        console.log('Error updating objective');
                    }
                });
            });
        </script>
    @endpush
</x-layout>
