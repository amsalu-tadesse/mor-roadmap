<x-layout>
    <x-breadcrump title='Activity Requests List' parent='Activity Requests' child='List' index="activities" />

    <div class='card'>
        <div class='card-header'>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <select id="partner_filter" class="form-control select2">
                            <option value="">All Partners</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='col-md-8 text-right'>
                    <div class="form-group">
                        @can('support-request: create')
                            <a href="{{ route('admin.activities.create') }}">
                                <button type='button' class='btn btn-primary'>Add New Activity Request</button>
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


    <x-partials.activity_modal :partners="$partners" :priorities="$priorities" :initiatives="$initiatives" :activityStatuses="$activityStatuses" :directorates="$directorates" />
    <x-show-modals.activity_show_modal />

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            function delete_row(element, row_id) {
                var url = "{{ route('admin.activities.destroy', ':id') }}";
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
                                    window.LaravelDataTables['activities-table'].ajax.reload();
                                    swalWithBootstrapButtons.fire('Deleted!', 'Activity request has been deleted.', 'success');
                                }
                            }
                        });
                    }
                });
            }

            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Activity Request');
            }

            $(document).ready(function() {
                // Initialize modal selects
                $('#activity_modal .select2').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    dropdownParent: $('#activity_modal')
                });

                // Initialize filter selects
                $('#partner_filter').select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });

                // Filter change events
                $('#partner_filter').on('change', function() {
                    window.LaravelDataTables['activities-table'].ajax.reload();
                });

                $('#activities-table').on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.activities.edit', ':id') }}";
                    url = url.replace(':id', row_id);
                    $('#activity_update_form :input').val('');
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                $('#activity_id').val(response.activity.id);
                                $('#sr_initiative_id').val(response.activity.initiative_id).trigger('change');
                                $('#sr_partner_id').val(response.activity.partner_id).trigger('change');
                                $('#sr_interested_partners').val(response.interested_partners).trigger('change');
                                $('#sr_directorates').val(response.directorates).trigger('change');
                                $('#sr_priority').val(response.activity.priority).trigger('change');
                                $('#sr_activities').val(response.activity.activities);
                                $('#sr_start_date').val(response.activity.start_date ? response.activity.start_date.substring(0, 10) : '');
                                $('#sr_end_date').val(response.activity.end_date ? response.activity.end_date.substring(0, 10) : '');
                                $('#sr_budget').val(response.activity.budget);
                                $('#sr_completion').val(response.activity.completion);
                                $('#sr_activity_status_id').val(response.activity.activity_status_id).trigger('change');
                                $('#sr_request_type').val(response.activity.request_type).trigger('change');
                                $('#sr_expenditure').val(response.activity.expenditure);
                                $('#activity_modal').modal('show');
                            }
                        }
                    });
                });

                $('#activities-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.activities.show', ':id') }}";
                    url = url.replace(':id', row_id);
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                $('#show_modal #partner').html(response.partnerName);
                                $('#show_modal #priority_show').html(response.priorityLabel);
                                $('#show_modal #activities_show').html(response.activity.activities);
                                $('#show_modal #start_date_show').html(response.start_date);
                                $('#show_modal #end_date_show').html(response.end_date);
                                $('#show_modal #budget_show').html(response.activity.budget);
                                $('#show_modal #completion_show').html(response.activity.completion);
                                $('#show_modal #activity_status_show').html(response.activityStatusName);
                                $('#show_modal #request_type_show').html(response.activity.request_type);
                                $('#show_modal #expenditure_show').html(response.activity.expenditure);
                                $('#show_modal #created_by').html(response.getCreatedBy);
                                $('#show_modal #created_at').html(response.created_at);
                                $('#show_modal').modal('show');
                            }
                        }
                    });
                });
            });

            $('#activity_form').on('submit', function(e) {
                e.preventDefault();
                var form_data = $(this).serialize();
                var row_id = $('#activity_id', $(this)).val();
                var url = "{{ route('admin.activities.update', ':id') }}";
                url = url.replace(':id', row_id);
                $.ajax({
                    url: url, type: 'PATCH', data: form_data, dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $('#activity_modal').modal('toggle');
                            window.LaravelDataTables['activities-table'].ajax.reload();
                            toastr.success('You have successfully updated the Activity Request.');
                        }
                    }
                });
            });
        </script>
    @endpush
</x-layout>
