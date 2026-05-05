<x-layout>
    <x-breadcrump title='Support Requests List' parent='Support Requests' child='List' index="support-requests" />

    <div class='card'>
        <div class='card-header'>
            <div class='col'>
                <div style='display: flex; justify-content:flex-end'>
                    @can('support-request: create')
                        <a href="{{ route('admin.support-requests.create') }}">
                            <button type='button' class='btn btn-primary'>Add New Support Request</button>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class='card-body'>
            {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
        </div>
    </div>


    <x-partials.support_request_modal :partners="$partners" :requestStatuses="$requestStatuses" :priorities="$priorities" :initiatives="$initiatives" />
    <x-show-modals.support_request_show_modal />

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            function delete_row(element, row_id) {
                var url = "{{ route('admin.support-requests.destroy', ':id') }}";
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
                                    window.LaravelDataTables['support-requests-table'].ajax.reload();
                                    swalWithBootstrapButtons.fire('Deleted!', 'Support request has been deleted.', 'success');
                                }
                            }
                        });
                    }
                });
            }

            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Support Request');
            }

            $(document).ready(function() {
                $('#support-requests-table').on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.support-requests.edit', ':id') }}";
                    url = url.replace(':id', row_id);
                    $('#support_request_update_form :input').val('');
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                $('#support_request_id').val(response.supportRequest.id);
                                $('#initiative_id').val(response.supportRequest.initiative_id);
                                $('#partner_id').val(response.supportRequest.partner_id);
                                $('#request_status_id').val(response.supportRequest.request_status_id);
                                $('#priority').val(response.supportRequest.priority);
                                $('#activities').val(response.supportRequest.activities);
                                $('#update_modal').modal('show');
                            }
                        }
                    });
                });

                $('#support-requests-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.support-requests.show', ':id') }}";
                    url = url.replace(':id', row_id);
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                $('#show_modal #partner').html(response.partnerName);
                                $('#show_modal #request_status').html(response.requestStatusName);
                                $('#show_modal #priority_show').html(response.priorityLabel);
                                $('#show_modal #activities_show').html(response.supportRequest.activities);
                                $('#show_modal #created_by').html(response.getCreatedBy);
                                $('#show_modal #created_at').html(response.created_at);
                                $('#show_modal').modal('show');
                            }
                        }
                    });
                });
            });

            $('#support_request_update_form').on('submit', function(e) {
                e.preventDefault();
                var form_data = $(this).serialize();
                var row_id = $('#support_request_id', $(this)).val();
                var url = "{{ route('admin.support-requests.update', ':id') }}";
                url = url.replace(':id', row_id);
                $.ajax({
                    url: url, type: 'PATCH', data: form_data, dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $('#update_modal').modal('toggle');
                            window.LaravelDataTables['support-requests-table'].ajax.reload();
                            toastr.success('You have successfully updated the Support Request.');
                        }
                    }
                });
            });
        </script>
    @endpush
</x-layout>
