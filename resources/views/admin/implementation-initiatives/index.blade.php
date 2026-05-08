<x-layout>
    <x-breadcrump title='Implementation Initiatives List' parent='Implementation Initiatives' child='List' index="implementation-initiatives" />

    <div class='card'>
        <div class='card-header'>
            <div class='col'>
                <div style='display: flex; justify-content:space-between'>
                    <h3 class="card-title mt-2">Manage Implementation Details</h3>
                </div>
            </div>
        </div>
        <div class='card-body'>
            {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
        </div>
    </div>

    @php
        $partners = \App\Models\Partner::all();
        $initiativeStatuses = \App\Models\InitiativeStatus::all();
    @endphp

    <x-partials.implementation_initiative_modal :partners="$partners" :initiativeStatuses="$initiativeStatuses" :objectives="$objectives" :directorates="$directorates" :implementationStatuses="$implementationStatuses" :themes="$themes" />
    <x-show-modals.implementation_initiative_show_modal />

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            function delete_row(element, row_id) {
                var url = "{{ route('admin.implementation-initiatives.destroy', ':id') }}";
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
                                    window.LaravelDataTables['implementation-initiatives-table'].ajax.reload();
                                    swalWithBootstrapButtons.fire('Deleted!', 'Initiative has been deleted.', 'success');
                                }
                            }
                        });
                    }
                });
            }

            if (@json(session('success_create'))) {
                toastr.success('You have successfully added a new Implementation Initiative');
            }

            $(document).ready(function() {
                $(document).on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.implementation-initiatives.edit', ':id') }}";
                    url = url.replace(':id', row_id);
                    $('#implementation_initiative_update_form :input').not(':submit, :button, :hidden').val('');
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                var initiative = response.initiative;
                                $('#initiative_id').val(initiative.id);
                                $('#name').val(initiative.name);
                                $('#directorate_id').val(initiative.directorate_id);
                                $('#implementation_status_id').val(initiative.implementation_status_id);
                                $('#start_date').val(response.start_date);
                                $('#end_date').val(response.end_date);
                                $('#budget').val(initiative.budget);
                                $('#expenditure').val(initiative.expenditure);
                                $('#partner_id').val(initiative.partner_id);
                                $('#completion').val(initiative.completion);
                                $('#initiative_status_id').val(initiative.initiative_status_id);
                                $('#request').val(initiative.request);
                                $('#note').val(initiative.note);

                                var themeId = initiative.theme_id;
                                if (themeId) {
                                    $.ajax({
                                        url: "{{ route('admin.get-objectives-by-theme') }}",
                                        type: "GET",
                                        data: { theme_id: themeId },
                                        dataType: "json",
                                        success: function(data) {
                                            $('#objective_id_modal').empty();
                                            $('#objective_id_modal').append('<option value="">Select Objective</option>');
                                            $.each(data, function(key, value) {
                                                $('#objective_id_modal').append('<option value="' + value.id + '">' + value.name + '</option>');
                                            });
                                            $('#theme_id_modal').val(themeId);
                                            $('#objective_id_modal').val(initiative.objective_id);
                                        }
                                    });
                                } else {
                                    $('#theme_id_modal').val('');
                                    $('#objective_id_modal').val(initiative.objective_id);
                                }

                                $('#update_modal').modal('show');
                            }
                        }
                    });
                });

                $(document).on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.implementation-initiatives.show', ':id') }}";
                    url = url.replace(':id', row_id);
                    $.ajax({
                        url: url, type: 'GET', dataType: 'json',
                        success: function(response) {
                            if (response.success == 1) {
                                $('#show_modal #name_show').html(response.initiative.name);
                                $('#show_modal #directorate_show').html(response.directorateName);
                                $('#show_modal #theme_show').html(response.themeName);
                                $('#show_modal #objective_show').html(response.objectiveName);
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

                $('#implementation_initiative_update_form').on('submit', function(e) {
                    e.preventDefault();
                    var form_data = $(this).serialize();
                    var row_id = $('#initiative_id', $(this)).val();
                    var url = "{{ route('admin.implementation-initiatives.update', ':id') }}";
                    url = url.replace(':id', row_id);
                    $.ajax({
                        url: url, type: 'PATCH', data: form_data, dataType: 'json',
                        success: function(data) {
                            if (data.success) {
                                $('#update_modal').modal('toggle');
                                window.LaravelDataTables['implementation-initiatives-table'].ajax.reload();
                                toastr.success('You have successfully updated Implementation Details.');
                            }
                        },
                        error: function(xhr) {
                            if(xhr.responseJSON && xhr.responseJSON.errors) {
                                let errors = xhr.responseJSON.errors;
                                let errorHtml = '<ul>';
                                $.each(errors, function(key, value) {
                                    errorHtml += '<li>' + value[0] + '</li>';
                                });
                                errorHtml += '</ul>';
                                toastr.error(errorHtml, 'Validation Error');
                            } else {
                                toastr.error('An error occurred.', 'Error');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layout>
