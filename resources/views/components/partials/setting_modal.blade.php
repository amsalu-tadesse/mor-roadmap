@props(['roles'])

<!-- /.modal -->
<div class="modal fade" id="update_modal">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Setting</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="setting_update_form">
                @csrf
                <div class="modal-body">
                    <!-- /.card-body -->
                    <!-- row -->
                    <div class="card-body row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-partials.input-form title="Name" name="name" type="input" />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" id="value1InputhGroup"> <!-- Add an ID for easy targeting -->
                                <x-partials.input-form title="Value1" name="value1" type="input" />
                            </div>
                            <div class="form-group" id="value1SwitchGroup"> <!-- Add an ID for easy targeting -->
                            <label for="status">Permission</label>
                                    <div class="px-3">
                                        <input type="checkbox" name="checkboxvalue1" id="checkboxvalue1"  data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" id="value2TextareaGroup"> <!-- Add an ID for easy targeting -->
                                <x-partials.textarea-input-form title="Value2" name="value2" />
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="hidden" name="setting_id" id="setting_id">
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