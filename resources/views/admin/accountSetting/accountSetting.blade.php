<x-layout>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Content Header (Page header) -->
    <x-breadcrump title="Account Setting" parent="Profile" child="Setting" />

    <!-- <body class="hold-transition sidebar-mini"> -->

    <!-- <div class="wrapper"> -->


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="/storage/{{ $profile->profile_image ?? '' }}" alt="User profile picture">
                            </div>


                            <h3 class="profile-username text-center">
                                {{ Auth::user()->first_name }} {{ Auth::user()->middle_name }}
                                {{ Auth::user()->last_name }}
                            </h3>


                            <p class="text-muted text-center">{{ $profile->position ?? '' }}</p>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">About Me</h3>
                        </div>
                        <!-- /.card-header -->
                        {{-- <div class="card-body">
                            <strong><i class="fas fa-book mr-1"></i> Education</strong>

                            <p class="text-muted">
                                {{ $profile->education ?? '' }}
                            </p>

                            <hr>



                            <hr>

                            <strong><i class="fas fa-pencil-alt mr-1"></i> Professions</strong>

                            <p class="text-muted">
                                {{ $profile->profession ?? '' }}

                            </p>


                        </div> --}}
                        <!-- /.card-body -->
                    </div>

                    <!-- /.card -->
                </div>

                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                {{-- <li class="nav-item"><a class="nav-link " href="#activity"
                                        data-toggle="tab">Activity</a></li> --}}
                                <!-- <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Overview</a></li>
                                     <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Edit Profile</a></li> -->
                                <li class="nav-item"><a
                                        class="nav-link  {{ Request::is('admin.postProfile') || session('success') ? 'active' : '' }}"
                                        href="#timeline" data-toggle="tab">Settings</a>
                                </li>
                                <li class="nav-item"><a
                                        class="nav-link {{ !Request::is('admin.postChangePassword') && !session('success') ? 'active' : '' }}"
                                        href="#settings" data-toggle="tab">Change Password</a></li>

                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane" id="activity">
                                    <!-- Post -->




                                    <!-- Post -->
                                    <div class="post">
                                        <h3>Activity coming soon</h3>

                                    </div>
                                    <!-- /.post -->
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane {{ Request::is('admin.postProfile') || session('success') ? 'active' : '' }}"
                                    id="timeline">
                                    <!-- The timeline -->
                                    <form class="form-horizontal" action="{{ route('admin.postProfile') }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group row">
                                            <label for="first_name" class="col-sm-2 col-form-label">First name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="first_name"
                                                    name="first_name" value="{{ Auth::user()->first_name }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="middle_name" class="col-sm-2 col-form-label">Middle name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="middle_name"
                                                    name="middle_name" value="{{ Auth::user()->middle_name }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="last_name" class="col-sm-2 col-form-label">Last name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="last_name"
                                                    name="last_name" value="{{ Auth::user()->last_name }}">
                                            </div>
                                        </div>



                                        <div class="form-group row">
                                            <label for="position" class="col-sm-2 col-form-label">Position</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="position"
                                                    name="position" value="{{ $profile->position ?? '' }}">
                                            </div>
                                        </div>
                                        {{-- <div class="form-group row">
                                            <label for="education" class="col-sm-2 col-form-label">Education</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="education"
                                                    name="education" value="{{ $profile->education ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="profession" class="col-sm-2 col-form-label">Profession</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="profession"
                                                    name="profession" value="{{ $profile->profession ?? '' }}">
                                            </div>
                                        </div> --}}
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <div class="form-group row">
                                            <label for="profile_image" class="col-sm-2 col-form-label">Profile
                                                Image</label>
                                            <div class="col-sm-10">
                                                <input type="file" class="form-control" id="profile_image"
                                                    name="profile_image" value="{{ $profile->profile_image ?? '' }}">
                                            </div>
                                        </div>
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </form>
                                </div>

                                <!-- /.tab-pane -->

                                <div class=" tab-pane {{ !Request::is('admin.postChangePassword') && !session('success') ? 'active' : '' }}"
                                    id="settings">

                                    <form class="form-horizontal" action="{{ route('admin.postChangePassword') }}"
                                        method="post">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="current_password" class="col-sm-2 col-form-label">Current
                                                Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="current_password"
                                                    name="current_password">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="new_password" class="col-sm-2 col-form-label">New
                                                Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="new_password"
                                                    name="new_password">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="new_password_confirmation"
                                                class="col-sm-2 col-form-label">Confirm New Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control"
                                                    id="new_password_confirmation" name="new_password_confirmation">
                                            </div>
                                        </div>
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </form><!-- End Change Password Form -->
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <!-- </div> -->
    <!-- /.content-wrapper -->

    <!-- </body> -->



</x-layout>
