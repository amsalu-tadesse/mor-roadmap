<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Zones List" parent="Zones" child="List" index="zones" />
    <!-- /.content-header -->

    <!-- /.content-Main -->
    <div class="card">
        <div class="card-header">
            <div class="col">
                <div class="d-flex justify-content-between">
                    <select name="region" id='region_filter' class="form-control w-25">
                        <option value="">--All Regions--</option>
                        @foreach ($regionLists as $region)
                            <option value="{{ $region->name }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                    @can('zone: create')
                    <a href="{{ route('admin.zones.create') }}"><button type="button" class="btn btn-primary">Add New
                            Zone</button></a>
                        @endcan
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
        </div>

        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <!-- /#updateModal -->
    <x-partials.zone_modal :regionLists='$regionLists' />
    <!-- /#updateModal -->
    <!-- /.content -->
    <!-- Custom Js contents -->
    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            });

            //Initialize Select2 Elements
            $('.role_select2').select2();
            $('#region_filter').select2();
        </script>

        <script>
            $('#region_filter').on('change', function() {
                window.LaravelDataTables["zones-table"].ajax.reload();
            });
        </script>

        <script>
            //delete user
            function delete_row(element, row_id) {
                var url = "{{ route('admin.zones.destroy', ':id') }}";
                url = url.replace(':id', row_id);
                console.log(url);

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
                            type: "DELETE",
                            url: url,
                            data: {
                                row_id: row_id,
                            },
                            dataType: "json",
                            success: function(data) {
                                console.log(data);
                                if (data.success) {
                                    window.LaravelDataTables["zones-table"].ajax.reload();
                                }
                            },
                            error: function(error) {
                                if (error.status ==
                                    422) { // when status code is 422, it's a validation issue

                                }
                                console.log('debug error here');
                            }
                        })
                        swalWithBootstrapButtons.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                        )
                    }
                })
            }

            if (@json(session('success_create'))) {

                toastr.success('You have successfuly added a new Zone.')
            }
            $(document).ready(function() {
                // Update record popup
                $('#zones-table').on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.zones.edit', ':id') }}";
                    url = url.replace(':id', row_id);

                    // AJAX request
                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: 'json',
                        success: function(response) {
                            console.log('success');

                            if (response.success == 1) {
                                console.log(response);
                                $('#zone_id').val(response.zone_id);
                                $('#name').val(response.name);

                                if (response.region_id)
                                    $('#region_id').val(response.region_id);

                                $('#update_modal').modal('show');

                            } else {
                                alert("Invalid ID.");
                            }
                        }
                    });
                });
            });


            $('#zone_update_form').on('submit', function(e) {
                e.preventDefault();
                form_data = $(this).serialize();
                row_id = $('#zone_id', $(this)).val()
                console.log(row_id);

                // var row_id = form_data['row_id']
                var url = "{{ route('admin.zones.update', ':id') }}";
                url = url.replace(':id', row_id);

                // AJAX request
                $.ajax({
                    url: url,
                    type: "PATCH",
                    data: form_data,
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            console.log(data);
                            $('#update_modal').modal('toggle');
                            window.LaravelDataTables["zones-table"].ajax.reload();
                            toastr.success('You have successfuly updated a Zone.')
                        }
                    },
                    error: function(error) {
                        console.log('error');
                    }
                });

            });
        </script>
    @endpush
    <!-- Custom Js contents -->
</x-layout>
