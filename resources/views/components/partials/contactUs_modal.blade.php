@props([''])

<!-- /.modal -->
<div class="modal fade" id="update_modal">
    <div class="modal-dialog modal-xl">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Message</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="replay_form">
                @csrf
                <div id="contactUs_body">
                    <div class="modal-body">
                        <!-- /.card-body -->
                        <!-- row -->
                        <div class="card-body row">
                            <div class="col-md-12">
                                <p>
                                    <x-partials.textarea-input-form-for-contact title="Message" name="message" />
                                </p>
                            </div>
                            <hr>
                            <div class="col-md-12 message-reply" id="replied_message">
                                 
                            </div>

                            <div class="col-md-12" id="replied_message_text_area">
                                <textarea style="width: 100%;" rows="4" cols="50" name="repliedMessage" id="repliedMessage" placeholder="write a message..." autocomplete="off" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" id="replay">
                    <input type="hidden" name="contactus_id" id="contactus_id">
                    <button type="submit" class="btn btn-primary show-button">Replay</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<style>
    /* .message-reply {
        color: white;
        background-color: #007bff;
        margin: 10px 0;
        text-align: right;
        padding: 10px 15px;
        border-radius: 10px;
        max-width: 70%;
    }
    .message-reply p {
        margin: 0;
    } */
</style>