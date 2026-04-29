<!-- /.modal -->
<div class="modal fade" id="show_modal">
    <div class="modal-dialog modal-xl">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">User Detail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- /.card-body -->
                <!-- row -->
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Full Name</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="full_name" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="email" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Mobile</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="mobile" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Is super Admin</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="is_superadmin" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Role(s)</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="user_roles" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="status" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email verified At</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="email_verified_at" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Password Changed</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="password_changed" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Created By</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="created_by" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Created At</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="created_at" />
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info show-button" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
