<x-layout>
    <x-breadcrump title='Request Statuses List' parent='Request Statuses' child='List' index="request-statuses" />

    <div class='card'>
        <div class='card-header'>
            <div class='col'>
                <div style='display: flex; justify-content:flex-end'>
                    @can('request-status: create')
                        <a href="{{ route('admin.request-statuses.create') }}">
                            <button type='button' class='btn btn-primary'>Add New Status</button>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class='card-body'>
            {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
        </div>
    </div>

    <x-partials.request_status_modal />
    <x-show-modals.request_status_show_modal />

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            function delete_row(element, row_id) {
                var url = "{{ route('admin.request-statuses.destroy', ':id') }}";
                url = url.replace(':id', row_id);

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: { confirmButton: 'btn btn-success mx-1', cancelButton: 'btn btn-danger' },
                    buttonsStyling: false
                });

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
                            type: 'DELETE', url: url, dataType: 'json',
                            success: function(data) {
                                if (data.success) {
                                    window.LaravelDataTables['request-statuses-table'].ajax.reload();
                                    swalWithBootstrapButtons.fire('Deleted!', 'Status has been deleted.', 'success');
                                }
                            }
                        });
                    }
                });
            }

            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Request Status');
            }

            $(document).ready(function() {
                $('#request-statuses-table').on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.request-statuses.edit', ':id') }}";
                    url = url.replace(':id', row_id);
                    $('#request_status_update_form :input').val('');
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                $('#request_status_id').val(response.requestStatus.id);
                                $('#name').val(response.requestStatus.name);
                                $('#update_modal').modal('show');
                            }
                        }
                    });
                });

                $('#request-statuses-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.request-statuses.show', ':id') }}";
                    url = url.replace(':id', row_id);
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                $('#show_modal #name').html(response.requestStatus.name);
                                $('#show_modal #created_by').html(response.getCreatedBy);
                                $('#show_modal #created_at').html(response.created_at);
                                $('#show_modal').modal('show');
                            }
                        }
                    });
                });
            });

            $('#request_status_update_form').on('submit', function(e) {
                e.preventDefault();
                var form_data = $(this).serialize();
                var row_id = $('#request_status_id', $(this)).val();
                var url = "{{ route('admin.request-statuses.update', ':id') }}";
                url = url.replace(':id', row_id);
                $.ajax({
                    url: url, type: 'PATCH', data: form_data, dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $('#update_modal').modal('toggle');
                            window.LaravelDataTables['request-statuses-table'].ajax.reload();
                            toastr.success('You have successfully updated the Status.');
                        }
                    }
                });
            });
        </script>
    @endpush
</x-layout>
