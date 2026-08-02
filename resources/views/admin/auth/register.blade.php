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
        MoR Reform initiatives
      </div>
      <div class="card-body">
        <p class="login-box-msg">Register a new membership</p>


        <form id="registration_form" action="{{ route('auth.signup') }}" method="POST">
          @csrf
          <div class="form-group mb-3">
            <div class="input-group">
              <input type="text" id="first_name" class="form-control" name="first_name" value="{{ old('first_name') }}" placeholder="First name" required autofocus>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <span class="invalid-feedback d-block small" id="first_name_err"></span>
          </div>

          <div class="form-group mb-3">
            <div class="input-group">
              <input type="text" id="middle_name" class="form-control" name="middle_name" value="{{ old('middle_name') }}" placeholder="Middle name" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <span class="invalid-feedback d-block small" id="middle_name_err"></span>
          </div>

          <div class="form-group mb-3">
            <div class="input-group">
              <input type="text" id="last_name" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <span class="invalid-feedback d-block small" id="last_name_err"></span>
          </div>

          <div class="form-group mb-3">
            <div class="input-group">
              <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email address" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <span class="invalid-feedback d-block small" id="email_err"></span>
          </div>

          <div class="form-group mb-3">
            <div class="input-group">
              <input type="tel" id="mobile" class="form-control" name="mobile" value="{{ old('mobile') }}" placeholder="Mobile number (e.g. 0912345678)" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-phone"></span>
                </div>
              </div>
            </div>
            <span class="invalid-feedback d-block small" id="mobile_err"></span>
          </div>

          <div class="form-group mb-3">
            <div class="row">
              <div class="col-8">
                <div class="icheck-primary">
                  <input type="checkbox" id="agreeTerms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                  <label for="agreeTerms">
                    I agree to the <a href="#terms_and_conditions" id="terms_and_conditions">terms</a>
                  </label>
                </div>
              </div>
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Register</button>
              </div>
            </div>
            <span class="invalid-feedback d-block small" id="terms_err"></span>
            <span class="invalid-feedback d-block small" id="general_err"></span>
          </div>
        </form>
        <x-partials.terms_and_conditions_show_modal :terms="$terms"/>

        <a href="{{route('login')}}" class="text-center">I already have a membership</a>
      </div>
    </div>
  </div>

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

      $('#registration_form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');

        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').text('');

        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Submitting...');

        $.ajax({
          url: $form.attr('action'),
          type: 'POST',
          data: $form.serialize(),
          dataType: 'json',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          },
          success: function(response) {
            if (response.success) {
              window.location.href = response.redirect || "{{ route('login') }}";
            }
          },
          error: function(xhr) {
            $submitBtn.prop('disabled', false).text('Register');
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
              var errors = xhr.responseJSON.errors;
              $.each(errors, function(field, messages) {
                var $input = $form.find('[name="' + field + '"]');
                if ($input.length) {
                  $input.addClass('is-invalid');
                }
                $('#' + field + '_err').text(messages[0]);
              });
            } else {
              var errorMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'An error occurred during registration.';
              $('#general_err').text(errorMsg);
            }
          }
        });
      });
    });
  </script>
</body>

</html>
