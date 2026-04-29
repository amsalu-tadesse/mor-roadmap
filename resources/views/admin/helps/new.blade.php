<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Add New Help" parent="Helps" child="Add New Help" />
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Add Help Form</h3>
        </div>
        <!-- /.card-header -->
        <!-- general form elements -->
        <!-- form start -->
        <form id="kpi_form" method="POST" action="{{ route('admin.helps.store') }}">
            @csrf
            <!-- /.card-body -->
            <!-- row -->
            <div class="card-body row">
                <!-- left column -->
                <div class="col-md-6">
                    <div class="form-group">
                        <!-- <x-partials.input-form title="Title" name="title" type="input" /> -->
                        <label for="title">Title</label>
                        {{-- {{ $help?->title }} --}}
                        <input type="text" name="title" value="{{ $help?->title }} " class="form-control"
                            id="title" placeholder="Enter title" required>
                    </div>
                    <div class="form-group">
                        {{-- <x-partials.input-form title="File" name="file" type="file" /> --}}
                        <label for="url">Video Url</label>
                        <input type="url" name="url" value="{{ $help?->url }}" class="form-control"
                            id="url" placeholder="Enter url" >
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- <div class="form-group">
                        <x-partials.input-form title="Route" name="route" type="input" />
                    </div> -->
                    <!-- <div class="form-group">
                        <x-partials.input-form title="Active" name="active" type="input" />
                    </div> -->

                    <!-- <div class="form-group">
                        <label for="status">Active</label>
                        <div class="px-3">
                            <input type="checkbox" name="active" id="active" data-bootstrap-switch
                                data-off-color="danger" data-on-color="success" {{ $help?->active ? 'checked' : '' }}>
                            <input type="hidden" name="active" id="active-hidden"
                                value="{{ $help?->active ? '1' : '0' }}">
                        </div>
                    </div> -->

                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="body">Body</label>
                        <textarea class="form-control body" name="body" id="body" rows="4" placeholder="Enter body">
                            {{ $help?->body }}

                            {{-- {!! old('body', 'test editor content') !!} --}}
                        </textarea>
                        {{-- <textarea name="body" class="form-control" id="body" placeholder="Enter body" required>
                        {{ $help?->body }}
                        </textarea> --}}


                    </div>
                    <input type="hidden" name="route" value="{{ request('route') }}">
                </div>
                <!--/.col (left) -->
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

<script>
    // $(document).ready(function() {
    //     var checkbox = $("input[data-bootstrap-switch]");

    //     // Initialize the Bootstrap Switch plugin
    //     checkbox.bootstrapSwitch();

    //     // Listen for the 'switchChange.bootstrapSwitch' event
    //     checkbox.on('switchChange.bootstrapSwitch', function(event, state) {
    //         // Update the value of the hidden input based on the Bootstrap Switch state
    //         // $('#active-hidden').val(state ? '1' : '0');
    //     });
    // });
</script>

{{-- <script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script> --}}
<script src="//cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('body', {
        filebrowserUploadUrl: "{{ route('upload', ['_token' => csrf_token()]) }}",
        filebrowserUploadMethod: 'form'
    });
</script>

{{-- <script>
$('#body').ckeditor(options);
</script> --}}
