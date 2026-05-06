<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title='Themes List' parent='Themes' child='List' index="themes" />
    <!-- /.content-header -->

    <!-- /.content-Main -->
    <div class='card'>
        <div class='card-header'>
            <div class='col'>
                <div style='display: flex; justify-content:flex-end'>
                    <div>
                        @can('theme: create')
                            <a href="{{ route('admin.themes.create') }}">
                                <button type='button' class='btn btn-fancy'>Add New Theme</button>
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
    <x-partials.theme_modal />
    <x-show-modals.theme_show_modal />
    <!-- /#updateModal -->
    <!-- /.content -->
    <!-- Custom Js contents -->
    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            //delete row
            function delete_row(element, row_id) {
                var url = "{{ route('admin.themes.destroy', ':id') }}";
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
                                    window.LaravelDataTables['themes-table'].ajax.reload();
                                    swalWithBootstrapButtons.fire(
                                        'Deleted!',
                                        'Theme has been deleted.',
                                        'success'
                                    )
                                }
                            },
                            error: function(error) {
                                console.log('Error deleting theme');
                            }
                        })
                    }
                })
            }

            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Theme')
            }

            $(document).ready(function() {
                // Update record popup
                $('#themes-table').on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.themes.edit', ':id') }}";
                    url = url.replace(':id', row_id);

                    $('#theme_update_form :input').val('');
                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            var theme = response.theme
                            if (response.success == 1) {
                                $('#theme_id').val(theme.id);
                                $('#name').val(theme.name);
                                $('#update_modal').modal('show');
                            } else {
                                alert('Invalid ID.');
                            }
                        }
                    });
                });

                //show
                $('#themes-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.themes.show', ':id') }}";
                    url = url.replace(':id', row_id);

                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            var theme = response.theme
                            if (response.success == 1) {
                                $('#show_modal #name').html(theme.name);
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

            $('#theme_update_form').on('submit', function(e) {
                e.preventDefault();
                form_data = $(this).serialize();
                row_id = $('#theme_id', $(this)).val()

                var url = "{{ route('admin.themes.update', ':id') }}";
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
                            window.LaravelDataTables['themes-table'].ajax.reload();
                            toastr.success('You have successfully updated the Theme.')
                        }
                    },
                    error: function(error) {
                        console.log('Error updating theme');
                    }
                });
            });
        </script>
    @endpush
</x-layout>
