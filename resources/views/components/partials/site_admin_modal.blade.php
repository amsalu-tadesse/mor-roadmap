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
            <form id="siteAdmin_update_form">
                @csrf
                <div class="modal-body">
                    <!-- /.card-body -->
                    <!-- row -->
                    <div class="card-body row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-partials.input-form title="Address" name="address" type="input" />
                            </div>
                            <div class="form-group">
                                <x-partials.input-form title="Email" name="email" type="input" />
                            </div>
                            <div class="form-group">
                                <x-partials.input-form title="Telephone" name="telephone" type="input" />
                            </div>
                            <div class="form-group">
                                <x-partials.input-form title="Facebook" name="facebook" type="input" />
                            </div>
                        </div>
                        <!-- /end left col -->
                        <!-- start right col -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-partials.input-form title="Twitter" name="twitter" type="input" />
                            </div>
                            <div class="form-group">
                                <x-partials.input-form title="YouTube" name="youtube" type="input" />
                            </div>
                            <div class="form-group">
                                <x-partials.input-form title="Linkedin" name="linkedin" type="input" />
                            </div>
                        </div>
                        <!-- /end right col -->
                        <!-- start another left col -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-partials.textarea-input-form title="About Us" name="aboutus" />
                            </div>
                        </div>
                        <!-- /end left col -->
                        <!-- start another right col -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-partials.textarea-input-form title="Location" name="location" />
                            </div>
                        </div>
                        <!-- /end Right col -->
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="hidden" name="siteAdmin_id" id="siteAdmin_id">
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
