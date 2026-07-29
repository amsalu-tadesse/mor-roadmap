<!-- Final Accept/Approve Modal (Green Button) -->
<div class="modal fade" id="accept_approval_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Finalize Initiative Approval</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="accept_approval_form">
                @csrf
                <input type="hidden" name="decision" id="accept_decision" value="approve">
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Description</label>
                            <blockquote class="quote-info" id="accept_description_view" style="margin: 0; padding: 10px; background-color: #f8f9fa; border-left: 3px solid #17a2b8;">
                                No description provided.
                            </blockquote>
                        </div>
                        <div class="form-group mt-3" id="accept_file_container">
                            <label>File Attachment</label>
                            <div>
                                <i class="fas fa-paperclip text-info mr-1"></i>
                                <a href="#" id="accept_file_link" target="_blank" class="text-info font-weight-bold">Download Attachment</a>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="accept_remarks">Reason <span class="required-field text-danger">*</span></label>
                            <textarea name="approval_remarks" class="form-control" id="accept_remarks" rows="3" placeholder="Enter reason for approval or rejection" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <div>
                        <button type="button" class="btn btn-danger btn-submit-accept" data-decision="reject">Reject</button>
                        <button type="button" class="btn btn-success btn-submit-accept" data-decision="approve">Approve</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
