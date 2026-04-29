@props(['roles'])

<!-- /.modal -->
<div class="modal fade" id="update_modal">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Help</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="help_update_form">
                @csrf
                <div class="modal-body">
                    <!-- /.card-body -->
                    <!-- row -->
                    <div class="card-body row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <x-partials.input-form title="Title" name="title" type="input" />
                                <span class="text-danger error" id="title_error"></span>
                            </div>
                            <div class="form-group">
                                <x-partials.input-form title="Video Url" name="url" type="url" />
                                <span class="text-danger error" id="url_error"></span>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">

                            <div class="form-group"> <!-- Add an ID for easy targeting -->
                                <label for="status">Permission</label>
                                <div class="px-3">
                                    <input type="checkbox" name="active" id="active" data-bootstrap-switch
                                        data-off-color="danger" data-on-color="success">
                                </div>
                            </div>

                        </div> --}}

                        <div class="col-md-12">
                            <div class="form-group">
                                <x-partials.textarea-input-form title="Body" name="body" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="hidden" name="help_id" id="help_id">
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
{{-- <script src="//cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('body', {
        filebrowserUploadUrl: "{{ route('upload', ['_token' => csrf_token()]) }}",
        filebrowserUploadMethod: 'form'
    });
</script> --}}
