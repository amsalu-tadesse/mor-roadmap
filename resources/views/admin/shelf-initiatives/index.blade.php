<x-layout>
    <x-breadcrump title='Shelf Initiatives' parent='Shelf Initiatives' child='List' index="shelf-initiatives" />

    <div class='card'>
        <div class='card-header'>
            <div class='col'>
                <div style='display: flex; justify-content:space-between'>
                    <h3 class="card-title mt-2">Shelf Initiatives</h3>
                    @can('shelf-initiative: create')
                        <a href="{{ route('admin.shelf-initiatives.create') }}">
                            <button type='button' class='btn btn-primary'>Add New Initiative</button>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class='card-body'>
            {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
        </div>
    </div>

    <x-show-modals.shelf_initiative_show_modal />

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Shelf Initiative');
            }

            $(document).ready(function() {
                $('#shelf-initiatives-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.shelf-initiatives.show', ':id') }}";
                    url = url.replace(':id', row_id);
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                $('#show_modal #name_show').html(response.initiative.name);
                                $('#show_modal #start_date_show').html(response.initiative.start_date ? response.initiative.start_date.substring(0, 10) : '');
                                $('#show_modal #end_date_show').html(response.initiative.end_date ? response.initiative.end_date.substring(0, 10) : '');
                                $('#show_modal #budget_show').html(response.initiative.budget);
                                $('#show_modal #expenditure_show').html(response.initiative.expenditure);
                                $('#show_modal #partner_show').html(response.partnerName);
                                $('#show_modal #completion_show').html(response.initiative.completion ? response.initiative.completion + '%' : '');
                                $('#show_modal #initiative_status_show').html(response.initiativeStatusName);
                                $('#show_modal #request_show').html(response.initiative.request);
                                $('#show_modal #created_by').html(response.getCreatedBy);
                                $('#show_modal #created_at').html(response.created_at);
                                $('#show_modal').modal('show');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layout>
