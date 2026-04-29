@props(['users',])

    <!-- /.modal -->
    <div class='modal fade' id='update_modal'>
        <div class='modal-dialog modal-lg'>
    
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Update Login Attempt Detail</h4>
                    <button type='button' class='close' data-dismiss='modal' aria-attribute='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <form id='login_attempt_update_form'>
                    @csrf
                    <div class='modal-body'>
                        <!-- /.card-body -->
                        <!-- row -->
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
                    </div>
                    <div class='modal-footer justify-content-between'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        <input type='hidden' name='login_attempt_id' id='login_attempt_id'>
                        <button type='submit' class='btn btn-info'>Save changes</button>
                    </div>
                </form>
                <!-- /#user_form -->
    
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    