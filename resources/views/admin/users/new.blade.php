<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Add New User" parent="Users" child="Add New User" index="users" />
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Add User Form</h3>
        </div>

        <!-- form start -->
        <form id="user_form" method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <!-- row -->
            <div class="card-body row">
                <!-- left column -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name">First Name<span class="required-field">*</span></label>
                        <input name="first_name" id="first_name" value="{{ old('first_name') }}" type="text" placeholder="Enter first name" class="form-control" required />
                        <span class="invalid-feedback d-block" id="first_name_err"></span>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name<span class="required-field">*</span></label>
                        <input name="last_name" id="last_name" value="{{ old('last_name') }}" type="text" placeholder="Enter last name" class="form-control" required />
                        <span class="invalid-feedback d-block" id="last_name_err"></span>
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile<span class="required-field">*</span></label>
                        <input name="mobile" id="mobile" value="{{ old('mobile') }}" type="tel" placeholder="Enter mobile number" class="form-control" required />
                        <span class="invalid-feedback d-block" id="mobile_err"></span>
                    </div>
                    <div class="form-group" style="display: flex; justify-content: space-between">
                        <div class="form-group">
                            <label for="status">User Status</label>
                            <div class="px-3">
                                <input type="checkbox" id="status" name="status" {{ old() ? (old('status') ? 'checked' : '') : 'checked' }} data-bootstrap-switch data-off-color="danger" data-on-color="success">
                            </div>
                        </div>
                    </div>
                </div>
                <!--/.col (left) -->

                <!-- right column -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="middle_name">Middle Name<span class="required-field">*</span></label>
                        <input name="middle_name" id="middle_name" value="{{ old('middle_name') }}" type="text" placeholder="Enter middle name" class="form-control" required />
                        <span class="invalid-feedback d-block" id="middle_name_err"></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address<span class="required-field">*</span></label>
                        <input name="email" id="email" value="{{ old('email') }}" type="email" placeholder="Enter Email Address" class="form-control" required />
                        <span class="invalid-feedback d-block" id="email_err"></span>
                    </div>
                    <div class="form-group">
                        <label>User Role(s)</label>
                        <div class="select2-blue">
                            <select name="user_roles[]" id="userRole" class="role_select2" multiple="multiple" data-placeholder="Pick User Role(s)" data-dropdown-css-class="select2-blue" style="width: 100%;">
                                @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ (in_array($role->id, old('user_roles', [])) || in_array((string)$role->id, old('user_roles', []))) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="invalid-feedback d-block" id="user_roles_err"></span>
                    </div>
                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->

            <div class="card-footer text-right">
                <span class="invalid-feedback d-block text-left mb-2" id="general_err"></span>
                <button type="submit" class="btn btn-info float-right mx-3">Submit</button>
                <a href="javascript:history.back()" class="btn btn-secondary float-right mx-3">Back</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.organization_select2').select2();
            $('#userRole').select2();
            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            });

            $('#user_form').on('submit', function(e) {
                e.preventDefault();
                var $form = $(this);
                var $submitBtn = $form.find('button[type="submit"]');

                $form.find('.is-invalid').removeClass('is-invalid');
                $form.find('.invalid-feedback').text('');

                $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Submitting...');

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.redirect || "{{ route('admin.users.index') }}";
                        }
                    },
                    error: function(xhr) {
                        $submitBtn.prop('disabled', false).text('Submit');

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                var baseField = field.split('.')[0];
                                var $input = $form.find('[name="' + baseField + '"], [name="' + baseField + '[]"]');
                                if ($input.length) {
                                    $input.addClass('is-invalid');
                                }
                                $('#' + baseField + '_err').text(messages[0]);
                            });
                        } else {
                            var errorMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'An error occurred while saving the user.';
                            $('#general_err').text(errorMsg);
                        }
                    }
                });
            });
        });
    </script>
    @endpush
</x-layout>

<style>
    .required-field {
        color: red;
        margin-left: 4px;
    }
</style>