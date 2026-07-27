<!-- Propose Approval Modal (Yellow Button) -->
<div class="modal fade" id="propose_approval_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Propose Initiative Approval</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="propose_approval_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="decision" id="propose_decision" value="approve">
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="propose_description">Description<span class="required-field text-danger">*</span></label>
                            <textarea name="approval_description" class="form-control" id="propose_description" rows="4" placeholder="Enter comments or description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="propose_file">File Attachment</label>
                            <div class="custom-file">
                                <input type="file" name="approval_file" class="custom-file-input" id="propose_file">
                                <label class="custom-file-label" for="propose_file">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Allowed types: pdf, doc, docx, png, jpg, jpeg, zip. Max size: 10MB.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <div>
                        <button type="button" class="btn btn-danger btn-submit-propose" data-decision="reject">Reject</button>
                        <button type="button" class="btn btn-warning text-white btn-submit-propose" data-decision="approve">Approve</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
