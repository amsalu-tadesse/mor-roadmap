<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Helps List" parent="Helps" child="List" />
    <!-- /.content-header -->

    <!-- /.content-Main -->
    <div class="card">
        <div class="card-header">

        </div>
        <!-- /.card-header -->
        <div class="card-body">
            {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
        </div>

        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <!-- /#updateModal -->
    <x-partials.help_modal />
    <x-show-modals.help_show_modal />
    <!-- /#updateModal -->
    <!-- /.content -->
    <!-- Custom Js contents -->
    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>
            $(document).ready(function() {
                var checkbox = $("input[data-bootstrap-switch]");
                // Initialize the Bootstrap Switch plugin
                checkbox.bootstrapSwitch();
                // Listen for the 'switchChange.bootstrapSwitch' event
                checkbox.


                on('switchChange.bootstrapSwitch', function(event, state) {
                    // Update the value of the hidden input based on the Bootstrap Switch state
                    $('#active-hidden').val(state ? '1' : '0');
                });
            });
        </script>
        <script src="//cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

        <script>
            //delete helps
            function delete_row(element, row_id) {
                var url = "{{ route('admin.helps.destroy', ':id') }}";
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
                                    window.LaravelDataTables["helps-table"].ajax.reload();
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

                toastr.success('You have successfuly added a new help.')
            }

            $(document).ready(function() {
                // Update record popup
                $('#helps-table').on('click', '#update_row', function() {
                   var row_id = $(this).data('row_id');
                   // var url = "{{ route('admin.helps.edit', ':id') }}";
                    url = url.replace(':id', row_id);

                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: 'json',
                        success: function(response) {
                            console.log('success');

                            if (response.success == 1) {
                                console.log(response);
                                $('#help_id').val(response.help_id);
                                $('#title').val(response.title);
                                var a = $('#body');
                                CKEDITOR.instances.body.setData(a.val(response.body));
                                // $('#body').val(response.body);
                                $('#url').val(response.url);
                                $('#route').val(response.route);

                                if (response.active == 1) {
                                    $("input[data-bootstrap-switch]").each(function() {
                                        $(this).bootstrapSwitch('state', true);
                                    });
                                } else {
                                    $("input[data-bootstrap-switch]").each(function() {
                                        $(this).bootstrapSwitch('state', false);
                                    });
                                }
                                $('#update_modal').modal('show');

                            } else {
                                alert("Invalid ID.");
                            }
                        }
                    });
                });
                //show
                $('#helps-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = "{{ route('admin.helps.show', ':id') }}";
                    url = url.replace(':id', row_id);

                    // AJAX request
                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: 'json',
                        success: function(data) {
                            console.log('success');
                            var help = data.help
                            if (data.success == 1) {
                                console.log(help);
                                $('#show_modal #help_id').html(help.help_id);
                                $('#show_modal #title').html(help.title);

                                $('#show_modal #body').html(help.body);
                                $('#show_modal #url').html(help.url);
                                $('#show_modal #route').html(help.route);
                                var active = help.active == 1 ? "YES" : "NO"
                                $('#show_modal #active').html(active);
                                $('#show_modal #created_by').html(data.getCreatedBy);
                                $('#show_modal #created_at').html(help.created_at);
                                $('#show_modal').modal('show');

                            } else {
                                alert("Invalid ID.");
                            }
                        }
                    });
                });
            });


            $('#help_update_form').on('submit', function(e) {
                e.preventDefault();
                var body = CKEDITOR.instances.body.getData();

                form_data = $(this).serialize();
                form_data += '&body=' + encodeURIComponent(body);

                row_id = $('#help_id', $(this)).val();

                console.log(row_id);


                var url = "{{ route('admin.helps.update', ':id') }}";
                url = url.replace(':id', row_id);

                // AJAX request
                $.ajax({
                    url: url,
                    type: "PUT",
                    data: form_data,
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            console.log(data);
                            CKEDITOR.instances.body.setData('');
                            $('#update_modal').modal('toggle');
                            window.LaravelDataTables["helps-table"].ajax.reload();
                            toastr.success('You have successfuly updated a Help.')
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
