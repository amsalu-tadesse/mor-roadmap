<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Add New User" parent="Users" child="Add New User" index="users" />
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Add User Form</h3>
        </div>
        <!-- /.card-header -->
        <!-- general form elements -->
        <!-- form start -->
        <form id="user_form" method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <!-- /.card-body -->
            <!-- row -->
            <div class="card-body row">
                <!-- left column -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="middle_name">First Name<span class="required-field">*</span></label>
                        <input name="first_name" value="{{ old('first_name') }}" type="input" placeholder="Enter first name" class="form-control" />
                        @error('first_name')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name<span class="required-field">*</span></label>
                        <input name="last_name" value="{{ old('last_name') }}" type="input" placeholder="Enter last name" class="form-control" />
                        @error('last_name')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile<span class="required-field">*</span></label>
                        <input name="mobile" value="{{ old('mobile') }}" type="tel" placeholder="Enter mobile number" class="form-control" />
                        @error('mobile')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="for-group" style="display: flex; justify-content: space-between">
                        <!-- <div class="form-group px-4">
                            <label for="superadmin_check">
                                <i class="nav-icon fas fa-user-lock" style="color: #db02f7;"></i>
                                <strong>Is Super Admin</strong></label>
                            <div class="icheck-success" id="superadmin_check">
                                <input name="is_superadmin" {{ old('is_superadmin') ? 'checked' : '' }} type="checkbox" id="superadmin">
                                <label for="superadmin">
                                </label>
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label for="status">User Status</label>
                            <div class="px-3">
                                <input type="checkbox" id="status" name="status" {{ old('status') ? 'checked' : '' }} data-bootstrap-switch data-off-color="danger" data-on-color="success">
                            </div>
                        </div>

                    </div>
                </div>
                <!--/.col (left) -->

                <!-- right column -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="middle_name">Middle Name<span class="required-field">*</span></label>
                        <input name="middle_name" value="{{ old('middle_name') }}" type="input" placeholder="Enter midle name" class="form-control" />
                        @error('middle_name')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="mobile">Email Address<span class="required-field">*</span></label>
                        <input name="email" value="{{ old('email') }}" type="email" placeholder="Enter Email Address" class="form-control" />
                        @error('email')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>User Role(s)</label>
                        <div class="select2-blue">
                            <select name="user_roles[]" id="userRole" class="role_select2" multiple="multiple" data-placeholder="Pick User Role(s)" data-dropdown-css-class="select2-blue" style="width: 100%;">
                                @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ in_array($role->id, old('user_roles', [])) ? ' selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group" data-select2-id="070">
                        <label>Organization</label>
                        <div class="select2-blue" data-select2-id="070">
                            <select name="organization_id" class="organization_select2 select2-hidden-accessible" id="organization_id"   data-dropdown-css-class="select2-blue" style="width: 100%;" data-select2-id="070" tabindex="-1" aria-hidden="true">
                                <option value="">Select organization</option>
                                @foreach ($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ old('organization_id') == $organization->id ? 'selected' : '' }}>
                                    {{ $organization->name }}
                                </option>
                                </option>
                                @endforeach
                            </select><span class="select2 select2-container select2-container--default select2-container--focus select2-container--open select2-container--above" dir="ltr" data-select2-id="2" style="width: 100%;"><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        </div>
                        @error('organization_id')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>


                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
            <!-- /.card-body -->
            <!-- /.card-footer -->
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-info float-right mx-3">Submit</button>
                <a href="javascript:history.back()" class="btn btn-secondary float-right mx-3">Back</a>
            </div>
            <!-- /.card-footer -->
        </form>
        <!-- /#user_form -->
    </div>
    <!-- /.card -->
    <!-- /.content -->

    <!-- Custom Js contents -->
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.organization_select2').select2();
        });
    </script>
    <script>
        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });

        //Initialize Select2 Elements
        $(document).ready(function() {
            $('#userRole').select2();

        });
    </script>
    @endpush
</x-layout>

<style>
    /* Define a CSS class for the red asterisk */
    .required-field {
        color: red;
        margin-left: 4px;
        /* Adjust the margin as needed for spacing */
    }
</style>