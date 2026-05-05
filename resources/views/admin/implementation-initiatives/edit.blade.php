<x-layout>
    <x-breadcrump title='Edit Implementation Initiative' parent='Implementation Initiatives' child='Edit' index="implementation-initiatives" />

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin.implementation-initiatives.update', $implementationInitiative->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Core Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Initiative Name<span class="required-field text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" id="name" value="{{ $implementationInitiative->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="objective_id">Objective<span class="required-field text-danger">*</span></label>
                                    <select name="objective_id" class="form-control select2" id="objective_id" required>
                                        <option value="">Select Objective</option>
                                        @foreach($objectives as $objective)
                                            <option value="{{ $objective->id }}" {{ $implementationInitiative->objective_id == $objective->id ? 'selected' : '' }}>{{ $objective->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="directorate_id">Directorate<span class="required-field text-danger">*</span></label>
                                    <select name="directorate_id" class="form-control select2" id="directorate_id" required>
                                        <option value="">Select Directorate</option>
                                        @foreach($directorates as $directorate)
                                            <option value="{{ $directorate->id }}" {{ $implementationInitiative->directorate_id == $directorate->id ? 'selected' : '' }}>{{ $directorate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="implementation_status_id">Implementation Status</label>
                                    <select name="implementation_status_id" class="form-control select2" id="implementation_status_id">
                                        <option value="">Select Implementation Status</option>
                                        @foreach($implementationStatuses as $status)
                                            <option value="{{ $status->id }}" {{ $implementationInitiative->implementation_status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-info mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Implementation Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" name="start_date" class="form-control" id="start_date" value="{{ $implementationInitiative->start_date ? $implementationInitiative->start_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" name="end_date" class="form-control" id="end_date" value="{{ $implementationInitiative->end_date ? $implementationInitiative->end_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="budget">Budget</label>
                                    <input type="text" name="budget" class="form-control" id="budget" value="{{ $implementationInitiative->budget }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="completion">Completion (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" name="completion" class="form-control" id="completion" value="{{ $implementationInitiative->completion }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="partner_id">Partner</label>
                                    <select name="partner_id" class="form-control select2" id="partner_id">
                                        <option value="">Select Partner</option>
                                        @foreach($partners as $partner)
                                            <option value="{{ $partner->id }}" {{ $implementationInitiative->partner_id == $partner->id ? 'selected' : '' }}>{{ $partner->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="initiative_status_id">Initiative Status</label>
                                    <select name="initiative_status_id" class="form-control select2" id="initiative_status_id">
                                        <option value="">Select Status</option>
                                        @foreach($initiativeStatuses as $status)
                                            <option value="{{ $status->id }}" {{ $implementationInitiative->initiative_status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="request">Request</label>
                                    <select name="request" class="form-control" id="request">
                                        <option value="">Select Request Type</option>
                                        <option value="New" {{ $implementationInitiative->request == 'New' ? 'selected' : '' }}>New</option>
                                        <option value="Current" {{ $implementationInitiative->request == 'Current' ? 'selected' : '' }}>Current</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea name="note" class="form-control" id="note" rows="3">{{ $implementationInitiative->note }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Initiative</button>
                        <a href="{{ route('admin.implementation-initiatives.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Support Requests</h3>
            <button type="button" class="btn btn-sm btn-success ml-auto" data-toggle="modal" data-target="#support_request_modal">
                <i class="fas fa-plus"></i> Add Support Request
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="support-requests-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Partner</th>
                        <th>Activities</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($implementationInitiative->supportRequests as $index => $request)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $request->partner->name ?? 'N/A' }}</td>
                            <td>{{ $request->activities }}</td>
                            <td>{{ $request->requestStatus->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $badge = $request->priority == 'H' ? 'danger' : ($request->priority == 'M' ? 'warning' : 'info');
                                    $label = $priorities[$request->priority] ?? $request->priority;
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ $label }}</span>
                            </td>
                            <td>
                                <button class='btn btn-sm edit-support' data-id="{{ $request->id }}" data-partner_id="{{ $request->partner_id }}" data-activities="{{ $request->activities }}" data-status_id="{{ $request->request_status_id }}" data-priority="{{ $request->priority }}">
                                    <i class='text-info far fa-edit'></i>
                                </button>
                                <button type="button" class="btn btn-sm delete-support" data-id="{{ $request->id }}">
                                    <i class='text-danger fas fa-trash'></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Support Request Modal -->
    <div class="modal fade" id="support_request_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="support_modal_title">Add Support Request</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="support_request_form" action="{{ route('admin.support-requests.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="support_method" value="POST">
                    <input type="hidden" name="initiative_id" value="{{ $implementationInitiative->id }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="sr_partner_id">Partner</label>
                            <select name="partner_id" id="sr_partner_id" class="form-control" required>
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sr_activities">Activities</label>
                            <textarea name="activities" id="sr_activities" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="sr_status_id">Request Status</label>
                            <select name="request_status_id" id="sr_status_id" class="form-control" required>
                                @foreach($requestStatuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sr_priority">Priority</label>
                            <select name="priority" id="sr_priority" class="form-control" required>
                                <option value="L">Low</option>
                                <option value="M" selected>Medium</option>
                                <option value="H">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            $('#support-requests-table').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            });

            $('.edit-support').click(function() {
                let id = $(this).data('id');
                let partner_id = $(this).data('partner_id');
                let activities = $(this).data('activities');
                let status_id = $(this).data('status_id');
                let priority = $(this).data('priority');

                $('#support_modal_title').text('Edit Support Request');
                $('#support_request_form').attr('action', '/admin/support-requests/' + id);
                $('#support_method').val('PATCH');
                $('#sr_partner_id').val(partner_id);
                $('#sr_activities').val(activities);
                $('#sr_status_id').val(status_id);
                $('#sr_priority').val(priority);
                $('#support_request_modal').modal('show');
            });

            $('#support_request_form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var method = $('#support_method').val();
                var data = form.serialize();

                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            $('#support_request_modal').modal('hide');
                            toastr.success('Support Request saved successfully');
                            // We need to reload the page or update the table via AJAX
                            // Since we have a DataTable initialized below, we can't easily reload just the partial
                            // but we can reload the page for now to see the changes.
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Something went wrong');
                    }
            });

            $(document).on('click', '.delete-support', function() {
                if (confirm('Are you sure you want to delete this support request?')) {
                    let id = $(this).data('id');
                    let url = "/admin/support-requests/" + id;
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Support Request deleted successfully');
                                location.reload();
                            }
                        }
                    });
                }
            });

            $('#support_request_modal').on('hidden.bs.modal', function () {
                $('#support_modal_title').text('Add Support Request');
                $('#support_request_form').attr('action', "{{ route('admin.support-requests.store') }}");
                $('#support_method').val('POST');
                $('#support_request_form')[0].reset();
            });
        });
    </script>
    @endpush
</x-layout>
