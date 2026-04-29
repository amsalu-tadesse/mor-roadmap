<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Roles List" parent="Roles" child="List" />
    <!-- /.content-header -->
    <!-- /.content-Main -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">User Roles and Permissions</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="mb-5"><button class="btn btn-primary float-right" data-toggle="modal"
                    data-target="#role_create_modal">Create New Role</button></div>
            <table id="datatable" class="table table-responsive-md table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="no-sort">No</th>
                        <th scope="col">Permissions</th>
                        @foreach ($roles as $role)
                            <th scope="col" class="no-sort" id="table_head_{{ $role->id }}">
                                <div>
                                    <div class="mb-2">
                                        <input style="width:auto" type="text" class="form-control" id="role_input"
                                            name="name" data-role_id="{{ $role->id }}"
                                            value="{{ $role->name }}" required hidden>
                                        <div class="text-danger error" id="update_name_error"></div>
                                    </div>
                                    <span data-toggle="tooltip" data-placement="bottom" title="dubble click to edit"
                                        id="role_span" ondblclick="display_input(this)">{{ ucwords($role->name) }}
                                    </span>
                                    <span style="cursor:pointer;" id="delete_role" class="text-danger p-2"
                                        onclick="delete_role(this, {{ $role->id }})"><i
                                            class="fa fa-trash"></i></span>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permissions as $key=>$permission)
                        <tr id="permission-{{ $permission->id }}">
                            <td>{{ $key + 1 }}</td>
                            <td class="">{{ $permission->name }}</td>
                            @php
                                $role_ids = $permission->roles->pluck('id')->toArray();
                            @endphp

                            @foreach ($roles as $role)
                                @php
                                    $class_yes = 'bg-success';
                                    $class_no = 'bg-danger';
                                    $css = '';
                                    if ($role->name == 'Super Admin') {
                                        $class_yes = 'bg-secondary';
                                        $class_no = 'bg-secondary';
                                        $css = 'pointer-events: none';
                                    }
                                @endphp

                                @if (in_array($role->id, $role_ids))
                                    <td class="table_column_{{ $role->id }}">



                                        <span class="badge {{ $class_yes }} text-light" role="button"
                                            onclick="update_role_permission(this, {{ $role->id }}, {{ $permission->id }})"
                                            style="{{ $css }}">Yes</span>
                                    </td>
                                @else
                                    <td class="table_column_{{ $role->id }}">
                                        <span class="badge {{ $class_no }} text-light" role="button"
                                            onclick="update_role_permission(this, {{ $role->id }}, {{ $permission->id }})"
                                            style="{{ $css }}">No</span>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @empty
                        {{-- <p>no data available</p> --}}
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <!-- /#createModal -->
    <!-- /.modal -->
    <div class="modal fade" id="role_create_modal">
        <div class="modal-dialog modal-lg">

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Role</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="role_create_form">
                    @csrf
                    <div class="modal-body">
                        <!-- /.card-body -->
                        <!-- row -->
                        <div class="card-body row">
                            <!-- column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-partials.input-form title="Role Name" name="name" type="input" />
                                    <span class="text-danger error" id="name_error"></span>
                                </div>
                            </div>
                            <!--/.col  -->
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="hidden" name="row_id" id="row_id">
                        <button type="submit" class="btn btn-info">Save changes</button>
                    </div>
                </form>
                <!-- /#user_form -->

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- /#createModal -->
    <!-- /.content -->
    <!-- Custom Js contents -->
    @push('scripts')
        <script>
            permissions = @json($permissions);

            $(document).ready(function() {

                var backendLabels = {!! json_encode($pageNumbers[0]) !!};
                var frontendLabels = {!! json_encode($pageNumbers[1]) !!};
                $('#datatable').DataTable({
                    responsive: true,
                    "aaSorting": [],
                    columnDefs: [{
                        targets: 'no-sort',
                        orderable: false,
                        searchable: false
                    }],
                    lengthMenu: [backendLabels, frontendLabels],
                    dom: "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6'B>\
                                                                                   <'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>>\
                                                                                   <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis']
                });

                // catch role create form submission
                $("#role_create_form").on("submit", function(event) {
                    event.preventDefault();

                    $form_data = $(this).serialize();

                    $.ajax({
                        url: "{{ route('admin.roles.store') }}",
                        enctype: 'multipart/form-data',
                        method: "POST",
                        data: $form_data,
                        dataType: 'json',
                        success: function(data) {
                            console.log('success');
                            location.reload(true);
                            // $('#datatable').DataTable().ajax.reload();
                            toastr.success('You have successfuly updated a user.')
                        },
                        error: function(error) {
                            console.log('errors');
                            $("span[id*=_error]").text("");

                            if (error.status ==
                                422) { // when status code is 422, it's a validation issue
                                $.each(error.responseJSON.errors, function(key, error) {
                                    $("#" + key + "_error").text(error);
                                });
                            }
                        }
                    })
                })
            });

            //display edit role and update
            function display_input(element) {
                console.log('triggered!');
                var role_input = $(element).prev().children().first();
                console.log(role_input);
                // var role_input = $('div input#role_input', $(element));

                role_input.removeAttr("hidden");
                // var role_span = $('span#role_span', $(element));
                var role_span = $(element);
                role_span.attr("hidden", "hidden");

                role_input.on('keypress', function(event) {
                    if (event.which == 13) {
                        event.preventDefault();
                        var role_value = role_input.val().trim();
                        var role_id = role_input.data('role_id');

                        // console.log(role_id);

                        role_span.text(role_value);
                        role_span.removeAttr("hidden");
                        role_input.attr("hidden", "hidden");
                        console.log('enter pressed!');

                        var url = '{{ route('admin.roles.update', ':id') }}';
                        url = url.replace(':id', role_id);
                        $.ajax({
                            url: url,
                            method: "PATCH",
                            data: {
                                name: role_value,
                                id: role_id
                            },
                            dataType: "json",
                            success: function(data) {
                                console.log(data);
                                console.log('success here');
                                $("#update_name_error").text('');
                            },
                            error: function(error) {
                                console.log(error);
                                if (error.status ==
                                    422) { // when status code is 422, it's a validation issue
                                    $.each(error.responseJSON.errors, function(key, error) {
                                        console.log(key);
                                        role_input.val('');
                                        role_input.removeAttr("hidden");
                                        $("#update_" + key + "_error").text(error);
                                    });
                                }
                                console.log('debug error here');
                            }
                        })
                    }
                });
            }

            // update role permissin matrics relationship
            function update_role_permission(element, role, permission) {
                $(element).text("processing ...");

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.permissions.store') }}",
                    data: {
                        role_id: role,
                        permission_id: permission
                    },
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        console.log('success here');
                        if (data.success) {
                            if (data.action == 1)
                                $(element).removeClass("bg-danger").addClass("bg-success").text("Yes")
                            else
                                $(element).removeClass("bg-success").addClass("bg-danger").text("No")
                        }
                    },
                    error: function(error) {
                        if (error.status == 422) { // when status code is 422, it's a validation issue
                            $.each(error.responseJSON.errors, function(key, error) {
                                $("#" + key + "_error").text(error);
                            });
                        }
                        // console.log(err);
                        console.log('debug error here');
                    }
                })
            }

            //delete role and make the table entries
            function delete_role(element, role_id) {
                var url = '{{ route('admin.roles.update', ':id') }}';
                url = url.replace(':id', role_id);

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
                                role_id: role_id,
                            },
                            dataType: "json",
                            success: function(data) {
                                console.log(data);
                                console.log('success here');
                                if (data.success) {
                                    $(element).parent().parent().remove();
                                    console.log("td.table_column_" + data.removed_role.id);
                                    // console.log($(`table#datatable tbody tr td.${ data.removed_role }`));
                                    console.log($("table#datatable tbody tr td.table_column_" + data
                                        .removed_role.id));

                                    $("table#datatable tbody tr td.table_column_" + data.removed_role.id)
                                        .each(function(
                                            index) {
                                            // console.log( index, this);
                                            this.remove();
                                            console.log('removed' + index);
                                        });

                                }
                            },
                            error: function(error) {
                                if (error.status ==
                                    422) { // when status code is 422, it's a validation issue

                                }
                                // console.log(err);
                                console.log('debug error here');
                            }
                        })
                        swalWithBootstrapButtons.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                        location.reload(true);

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
        </script>

        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
    @endpush
    <!-- Custom Js contents -->
</x-layout>
