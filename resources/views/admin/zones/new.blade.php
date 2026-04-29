<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Add New Zones" parent="Zones" child="Add New Zones" index="zones" />
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Add Zones Form</h3>
        </div>
        <!-- /.card-header -->
        <!-- general form elements -->
        <!-- form start -->
        <form id="zones" method="POST" action="{{ route('admin.zones.store') }}">
            @csrf
            <!-- /.card-body -->
            <!-- row -->
            <div class="card-body row">
                <!-- left column -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="region">Region</label>
                        <select id='region' class="form-control" name="region_id">
                        <option value="">Select Region</option>
                            @foreach ($regionLists as $regionList)
                            <option value="{{$regionList->id}}">{{$regionList->name}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <x-partials.input-form title="Name" name="name" type="input" />
                    </div>

                </div>
                <!--/.col (left) -->
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

</x-layout>
