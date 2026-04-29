@props(['roles','organizations'])

<!-- /.modal -->
<div class="modal fade" id="update_modal">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update User Detail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="user_update_form">
                @csrf
                <div class="modal-body">
                    <!-- /.card-body -->
                    <!-- row -->
                    <div class="card-body row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-partials.input-form title="First Name" name="first_name" type="input" />
                                <span id="first_name_error" class="invalid-feedback d-block"></span>
                            </div>
                            <div class="form-group">
                                <x-partials.input-form title="Last Name" name="last_name" type="input" />
                                <span class="text-danger error" id="last_name_error"></span>
                            </div>
                            <div class="form-group">
                                <x-partials.input-form title="Mobile Number" name="mobile" type="tel" />
                                <span class="text-danger error" id="mobile_error"></span>
                            </div>
                            <div class="for-group" style="display: flex; justify-content: space-between">
                                <!-- <div class="form-group px-3">
                                    <label for="superadmin_check">
                                        <i class="nav-icon fas fa-user-lock" style="color: #db02f7;"></i>
                                        <strong>Is Super Admin</strong></label>
                                    <div class="icheck-success" id="superadmin_check">
                                        <input name="is_superadmin" type="checkbox" id="is_superadmin">
                                        <label for="is_superadmin">
                                        </label>
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label for="status">User Status</label>
                                    <div class="px-3">
                                        <input type="checkbox" name="status" id="status" {{ old('status') }} data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/.col (left) -->

                        <!-- right column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-partials.input-form title="Middle Name" name="middle_name" type="input" />
                                <span class="text-danger error" id="middle_name_error"></span>
                            </div>
                            <div class="form-group">
                                <x-partials.input-form title="Email Address" name="email" type="email" />
                                <span class="text-danger error" id="email_error"></span>
                            </div>
                            <div class="form-group">
                                <label>User Role(s)</label>
                                <div class="select2-blue">
                                    <select name="user_roles[]" class="role_select2" multiple="multiple" data-placeholder="Pick User Role(s)" data-dropdown-css-class="select2-blue" style="width: 100%;" id="user_roles">
                                        @foreach ($roles as $role)
                                        <option class="{{ $role->id }}" value="{{ $role->id }}">
                                            {{ $role->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" data-select2-id="080">
                            <label>Organization</label>
                            <div class="select2-blue" data-select2-id="080">
                                <select name="organization_id" class="organizations_select2 select2-hidden-accessible" id="organization_id" data-placeholder="" data-dropdown-css-class="select2-blue" style="width: 100%;" data-select2-id="080" tabindex="-1" aria-hidden="true">
                                    <option value="">Select organization</option>
                                    @foreach ($organizations as $organization)
                                    <option value="{{ $organization->id }}">
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
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="hidden" name="row_id" id="row_id">
                    <button type="submit" class="btn btn-info">Save changes</button>
                </div>
            </form>
            <!-- /#user_form -->
            <!-- /#user_form -->

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@push('scripts')
<script>

</script>
@endpush