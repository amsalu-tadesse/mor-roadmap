<!-- /.modal -->
<div class="modal fade" id="show_modal">
    <div class="modal-dialog modal-xl">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Help Detail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- /.card-body -->
                <!-- row -->
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="title" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Videol Url</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="url" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Route</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="route" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Body</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="body" />
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Active</label>
                        <div class="col-sm-10">
                            <x-partials.textarea-input-for-show-modals name="active" />
                        </div>
                    </div> --}}
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
                <!-- /.row -->
                <!-- /.card-body -->
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info show-button" data-dismiss="modal">Close</button>
            </div>
            <!-- /#user_form -->

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
