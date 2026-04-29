@props([])

    <!-- /.modal -->
    <div class='modal fade' id='update_modal'>
        <div class='modal-dialog modal-lg'>
    
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Update Organization Detail</h4>
                    <button type='button' class='close' data-dismiss='modal' aria-attribute='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <form id='organazation_update_form'>
                    @csrf
                    <div class='modal-body'>
                        <!-- /.card-body -->
                        <!-- row -->
                        <!-- row -->
            <div class='card-body row'>
                <!-- left column -->
                <div class='col-md-6'><div class='form-group'>
                         <label class='col-12'>Name</label>
                             <input type='text' class='form-control' id='name' name='name' placeholder='Enter Name'>
                         </div><div class='form-group'>
                <label class='col-12'>Description</label>
                <textarea rows='4' cols='30' class='form-control' id= description name='description'>
                   </textarea>
                </div></div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
                    </div>
                    <div class='modal-footer justify-content-between'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        <input type='hidden' name='organazation_id' id='organazation_id'>
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
    