<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> Registration Page </title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition register-page">

  <div class="register-box">
    <div class="card card-outline card-primary">
    <div class="card-header text-center" style="font-weight: bold; font-size:40px">
          UserMangment
            </div>
      <div class="card-body">
        <p class="login-box-msg">Register a new membership</p>
        @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
          {{ Session::get('message') }}
        </div>
        @endif
        <form action="{{ route('auth.signup') }}" method="POST">
          @csrf
          <div class="input-group mb-3">
            <input type="text" id="first_name" class="form-control" name="first_name" value="{{ old('first_name') }}" placeholder="First name" required autofocus>
            @if ($errors->has('first_name'))
            <span class="text-danger">{{ $errors->first('first_name') }}</span>
            @endif
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3  ">
            <input type="text" id="middle_name" class="form-control" name="middle_name" value="{{ old('middle_name') }}" placeholder="Middle name" required autofocus>
            @if ($errors->has('middle_name'))
            <span class="text-danger">{{ $errors->first('middle_name') }}</span>
            @endif
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="text" id="last_name" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" required autofocus>
            @if ($errors->has('last_name'))
            <span class="text-danger">{{ $errors->first('last_name') }}</span>
            @endif
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          @if ($errors->has('email'))
          <span class="text-danger">{{ $errors->first('email') }}</span>
          @endif



          <div class="input-group mb-3">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-building"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                <label for="agreeTerms">
                  I agree to the <a href="#terms_and_conditions" id="terms_and_conditions">terms</a>
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <x-partials.terms_and_conditions_show_modal :terms="$terms"/>
        <!-- Social -->
        <!-- <div class="social-auth-links text-center">
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i>
          Sign up using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i>
          Sign up using Google+
        </a>
      </div> -->

        <a href="{{route('login')}}" class="text-center">I already have a membership</a>
      </div>
      <!-- /.form-box -->
    </div><!-- /.card -->
  </div>
  <!-- /.register-box -->

  <!-- jQuery -->
  <script src="../../assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../assets/dist/js/adminlte.min.js"></script>

  <script>
    $(document).ready(function() {
      $(document).on('click', '#terms_and_conditions', function() {
        $('#terms_and_conditions_show_modal').modal('show');
      });
    });

    document.getElementById('myForm').addEventListener('submit', function (event) {
    // Check if there are errors in other input fields
    if (/* Check for errors in other fields */) {
        // Prevent the form from submitting
        event.preventDefault();

        // Optionally, display an error message to the user
        alert('Please correct the errors before submitting.');

        // Ensure the "I agree" checkbox remains checked
        document.getElementById('agreeTerms').checked = true;
    }
});

  </script>
</body>

</html>
