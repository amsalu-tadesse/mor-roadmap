<!-- Propose Approval Modal (Yellow Button) -->
<div class="modal fade" id="propose_approval_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Request Initiative Approval</h4>
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
                            <label for="propose_description">Description <span class="required-field text-danger">*</span></label>
                            <div id="propose_past_descriptions" class="mb-2" style="display: none;"></div>
                            <textarea name="approval_description" class="form-control" id="propose_description" rows="3" placeholder="Enter comments or description" required></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label for="propose_file">File Attachment</label>
                            <div id="propose_past_files" class="mb-2" style="display: none;"></div>
                            <div class="custom-file">
                                <input type="file" name="approval_file" class="custom-file-input" id="propose_file">
                                <label class="custom-file-label" for="propose_file">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Allowed types: pdf, doc, docx, png, jpg, jpeg, zip. Max size: 10MB.</small>
                        </div>
                        <div class="form-group mt-3" id="propose_remarks_container" style="display: none;">
                            <label>Reason / Remarks</label>
                            <div id="propose_remarks_view"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <div>
                        <button type="button" class="btn btn-warning text-black btn-submit-propose" data-decision="approve">Request</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
