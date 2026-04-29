<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Add New Region" parent="Region" child="Add New Region" index="regions" />
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Add Region Form</h3>
        </div>
        <!-- /.card-header -->
        <!-- general form elements -->
        <!-- form start -->
        <form id="regions_form" method="POST" action="{{ route('admin.regions.store') }}">
            @csrf
            <!-- /.card-body -->
            <!-- row -->
            <div class="card-body row">
                <!-- left column -->
                <div class="col-md-6">
                    <div class="form-group">
                        <x-partials.input-form title="Name" name="name" type="input" />
                    </div>

                    <div class="form-group">
                        <x-partials.input-form title="Latitude" name="latitude" type="input" />
                    </div>

                    <div class="form-group">
                        <x-partials.input-form title="Longitude" name="longitude" type="input" />
                    </div>

                    <div class="form-group">
                        <label>Color:</label>
                        <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
                            <input type="text" class="form-control" name="color" data-original-title=""
                                title="">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-square"></i></span>
                            </div>
                        </div>
                        @error('color')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- <div class="form-group" id="is_cityadministration"> <!-- Add an ID for easy targeting -->
                            <label for="status">Is city administration</label>
                                    <div class="px-3">
                                        <input type="checkbox" name="is_cityadministration" id="is_cityadministration"  data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                    </div>
                            </div> --}}
                </div>
                {{-- <div class="col-md-6">
                            <div class="form-group">
                                <x-partials.input-form title="Ordering" name="ordering" type="input" />
                            </div>
                        </div> --}}
            </div>
            <!-- /.row -->
            <!-- /.card-body -->
            <!-- /.card-footer -->
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-info float-right mx-3">Submit</button>
                <a href="javascript:history.back()" class="btn btn-secondary float-right mx-3">Back</a>
            </div>
            <!-- /.card-footer -->
        </form>
        <!-- /#user_form -->

    </div>
    <!-- /.card -->
    <!-- /.content -->

    <!-- Custom Js contents -->
    @push('scripts')
        <script>
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            $('.my-colorpicker2').on('colorpickerChange', function(event) {
                $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
            })
            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            });
        </script>
    @endpush
</x-layout>
