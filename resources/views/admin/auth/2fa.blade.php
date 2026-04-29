<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UserMangment | 2FA </title>

    <style>
        body {
            background: #EDEBED url("{{ asset('assets/img/login1.jpg') }}");
            opacity: 0.65;
            visibility: hidden;
            /* Hide the body element initially */
        }

        body.loaded {
            visibility: visible;
        }
    </style>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page loaded">
    <div class="login-box" style="border: 5px solid #3f6791;">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
        <div class="card-header text-center" style="font-weight: bold; font-size:40px">
           UserMangment
            </div>
            <div class="card-body">
                <p class="login-box-msg">Two Factor Authentication</p>
                @if (Session::has('message'))
                    <div class="alert alert-danger" role="alert">
                        {{ Session::get('message') }}
                    </div>
                @endif
                <form action="{{ route('login.user') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="twofa_code" id="twofa_code" class="form-control" placeholder="Enter the authentication code sent to your email." name="twofa_code"
                            required>
                        @if ($errors->has('twofa_code'))
                            <span class="text-danger">{{ $errors->first('twofa_code') }}</span>
                        @endif
                    </div>

                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Verify
                        </button>
                    </div>
                </form>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="../../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../assets/dist/js/adminlte.min.js"></script>
</body>

</html>
