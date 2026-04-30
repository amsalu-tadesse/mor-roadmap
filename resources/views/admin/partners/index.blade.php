<x-layout>
    <x-breadcrump title='Partners List' parent='Partners' child='List' index="partners" />

    <div class='card'>
        <div class='card-header'>
            <div class='col'>
                <div style='display: flex; justify-content:flex-end'>
                    <div>
                        @can('partner: create')
                            <a href="{{ route('admin.partners.create') }}">
                                <button type='button' class='btn btn-primary'>Add New Partner</button>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class='card-body'>
            {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
        </div>
    </div>

    <x-partials.partner_modal />
    <x-show-modals.partner_show_modal />

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            function delete_row(element, row_id) {
                var url = "{{ route('admin.partners.destroy', ':id') }}";
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
                                    window.LaravelDataTables['partners-table'].ajax.reload();
                                    swalWithBootstrapButtons.fire('Deleted!', 'Partner has been deleted.', 'success')
                                }
                            }
                        })
                    }
                })
            }

            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Partner')
            }

            $(document).ready(function() {
                $('#partners-table').on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.partners.edit', ':id') }}";
                    url = url.replace(':id', row_id);

                    $('#partner_update_form :input').val('');
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                $('#partner_id').val(response.partner.id);
                                $('#name').val(response.partner.name);
                                $('#update_modal').modal('show');
                            }
                        }
                    });
                });

                $('#partners-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.partners.show', ':id') }}";
                    url = url.replace(':id', row_id);

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                $('#show_modal #name').html(response.partner.name);
                                $('#show_modal #created_by').html(response.getCreatedBy);
                                $('#show_modal #created_at').html(response.created_at);
                                $('#show_modal').modal('show');
                            }
                        }
                    });
                });
            });

            $('#partner_update_form').on('submit', function(e) {
                e.preventDefault();
                form_data = $(this).serialize();
                row_id = $('#partner_id', $(this)).val();

                var url = "{{ route('admin.partners.update', ':id') }}";
                url = url.replace(':id', row_id);

                $.ajax({
                    url: url,
                    type: 'PATCH',
                    data: form_data,
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $('#update_modal').modal('toggle');
                            window.LaravelDataTables['partners-table'].ajax.reload();
                            toastr.success('You have successfully updated the Partner.')
                        }
                    }
                });
            });
        </script>
    @endpush
</x-layout>
