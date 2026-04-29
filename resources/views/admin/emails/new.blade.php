<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Add New Email" parent="Email" child="Add New Email" />
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Add Email Form</h3>
        </div>
        <!-- /.card-header -->
        <!-- general form elements -->
        <!-- form start -->
        <form id="email_form" method="POST" action="{{ route('admin.emails.store') }}">
            @csrf
            <!-- /.card-body -->
            <!-- row -->
            <div class="card-body row">
                <!-- left column -->
                <div class="col-md-6">
                    <div class="form-group">
                        <x-partials.input-form title="Code" name="code" type="input" />
                    </div>
                    </div>
                    <!-- /left column -->

                    <!-- right column -->
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
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
            <!-- /.card-body -->
            <!-- /.card-footer -->
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-info float-right mx-3">Submit</button>
            </div>
            <!-- /.card-footer -->
        </form>
        <!-- /#user_form -->

    </div>
    <!-- /.card -->
    <!-- /.content -->

    <!-- Custom Js contents -->

</x-layout>