<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title='Add New Login Attempt' parent='Login Attempt' child='Add New Login Attempt' />
    <!-- /.content-header -->

    <!-- Main content -->
    <div class='card card-info'>
        <div class='card-header'>
            <h3 class='card-title'>Add Login Attempt Form</h3>
        </div>
        <!-- /.card-header -->
        <!-- general form elements -->
        <!-- form start -->
        <form id='login_attempt_form' method='POST' action="{{ route('admin.login-attempts.store') }}">
            @csrf
            <!-- /.card-body -->
            <!-- row -->
            <div class='card-body row'>
                <!-- left column -->
                <div class='col-md-6'><div class='form-group'>
                     <label class='col-12'>IP Address</label>
                         <input type='text' class='form-control' id='ip_address' name='ip_address' placeholder='Enter IP Address'>
                     </div><div class='form-group'>
                     <label class='col-12'>Proxy</label>
                         <input type='text' class='form-control' id='proxy' name='proxy' placeholder='Enter Proxy'>
                     </div><div class='form-group'>
                     <label class='col-12'>Login Time</label>
                         <input type='text' class='form-control' id='login_time' name='login_time' placeholder='Enter Login Time'>
                     </div><div class='form-group'>
                     <label class='col-12'>Logout Time</label>
                         <input type='text' class='form-control' id='logout_time' name='logout_time' placeholder='Enter Logout Time'>
                     </div>   <div class='form-group'>
                    <label for='users'>User</label>
                    <select name='user_id' class='users_select2 select2 form-control' id='user_id' data-dropdown-css-class='select2-blue'>
                        <option value='none' selected disabled>Select a users</option>
                        @foreach ($users as  $login_attempt)
                        <option value='{{$login_attempt->id }}'>

                         {{$login_attempt->first_name }} {{$login_attempt->middle_name }}                        
                        </option>
                        @endforeach
                    </select>
                </div></div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
            <!-- /.card-body -->
            <!-- /.card-footer -->
            <div class='card-footer text-right'>
                <button type='submit' class='btn btn-info float-right mx-3'>Submit</button>
            </div>
            <!-- /.card-footer -->
        </form>
        <!-- /#user_form -->

    </div>
    <!-- /.card -->
    <!-- /.content -->

    <!-- Custom Js contents -->

    @push('scripts')
    <script>$('.users_select2').select2();</script>
      @endpush

</x-layout>
