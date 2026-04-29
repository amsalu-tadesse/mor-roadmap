@props([''])

<!-- /.modal -->
<div class="modal fade" id="update_modal">
    <div class="modal-dialog modal-xl">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Custom Exception Detail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div id="customException_body">
                <div class="modal-body">
                    <!-- /.card-body -->
                    <!-- row -->
                    <div class="card-body row">
                        <div class="col-md-12">
                            <p>
                               
                                <x-partials.textarea-input-form-for-cex title="Description" name="description" />
                                 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->