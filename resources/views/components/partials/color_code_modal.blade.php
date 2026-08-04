@props([])

    <!-- /.modal -->
    <div class='modal fade' id='update_modal'>
        <div class='modal-dialog modal-lg'>

            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Update Color Code Detail</h4>
                    <button type='button' class='close' data-dismiss='modal' aria-attribute='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <form id='color_code_update_form'>
                    @csrf
                    <div class='modal-body'>
                        <!-- /.card-body -->
                        <!-- row -->
                        <!-- row -->
            <div class='card-body row'>
                <!-- left column -->
                <div class='col-md-6'><div class='form-group'>
                         <label class='col-12'>Label</label>
                             <input type='text' class='form-control' id='label' name='label' placeholder='Enter Label'>
                         </div><div class='form-group'>
                         <label class='col-12'>Min</label>
                             <input type='text' class='form-control' id='min' name='min' placeholder='Enter Min'>
                         </div><div class='form-group'>
                         <label class='col-12'>Max</label>
                             <input type='text' class='form-control' id='max' name='max' placeholder='Enter Max'>
                         </div>

                         {{-- <div class='form-group'>
                         <label class='col-12'>Color</label>
                             <input type='text' class='form-control' id='color' name='color' placeholder='Enter Color'>
                         </div> --}}

                         <div class='form-group'>
    <label class='col-12'>Color</label>
    <div class="input-group">
        <input
            type="color"
            class="form-control form-control-color"
            id="color"
            name="color"
            value="#194da8">
        <input
            type="text"
            class="form-control"
            id="colorText"
            value="#194da8"
            >
    </div>
</div>


                        </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
                    </div>
                    <div class='modal-footer justify-content-between'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        <input type='hidden' name='color_code_id' id='color_code_id'>
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
