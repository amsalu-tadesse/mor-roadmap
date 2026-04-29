<x-layout>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Site Admins List" parent="Site Admins" child="List"/>
    <!-- /.content-header -->

    <!-- /.content-Main -->

    <div class="card">

        <form class="form-horizontal" method="POST" action="{{ route('admin.updateaddress', $siteAdmin->id) }}">
            {{ csrf_field() }}
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="name" name="name"
                            value="{{ $siteAdmin->name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Address</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="address" name="address"
                            value="{{ $siteAdmin->address }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="email" name="email"
                            value="{{ $siteAdmin->email }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Telephone</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="telephone" name="telephone"
                            value="{{ $siteAdmin->telephone }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Facebook</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="facebook" name="facebook"
                            value="{{ $siteAdmin->facebook }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Twitter</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="twitter" name="twitter"
                            value="{{ $siteAdmin->twitter }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">YouTube</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="youtube" name="youtube"
                            value="{{ $siteAdmin->youtube }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Linkedin</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="linkedin" name="linkedin"
                            value="{{ $siteAdmin->linkedin }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">About Us</label>
                    <div class="col-sm-10">
                        <textarea type="input" class="form-control" id="aboutus" name="aboutus">{{ $siteAdmin->aboutus }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Location</label>
                    <div class="col-sm-10">
                        <textarea type="input" class="form-control" id="location" name="location">{{ $siteAdmin->location }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Intro Video</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="intro_video" name="intro_video"
                            value="{{ $siteAdmin->intro_video }}">
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-info float-right">Update</button>
            </div>

        </form>

        <!-- /.card-body -->
    </div>
    <!-- /.card -->


</x-layout>
