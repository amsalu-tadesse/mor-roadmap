@props(['roles'])

<!-- /.modal -->
<div class="modal fade" id="update_modal">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Email Detail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="email_update_form">
                @csrf
                <div class="modal-body">
                    <!-- /.card-body -->
                    <!-- row -->
                    <div class="card-body row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-partials.input-form title="Subject" name="subject" type="input" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-partials.textarea-input-form title="Body" name="body" />
                            </div>
                        </div>
                    </div>
                    <!--/.col (left) -->
                </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="hidden" name="email_id" id="email_id">
            <button type="submit" class="btn btn-info">Save changes</button>
        </div>
        </form>
        <!-- /#user_form -->

    </div>
    </div>
</div>
<!-- /.modal-content -->