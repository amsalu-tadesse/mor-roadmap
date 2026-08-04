<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title='Add New Color Code' parent='Color Code' child='Add New Color Code' />
    <!-- /.content-header -->

    <!-- Main content -->
    <div class='card card-info'>
        <div class='card-header'>
            <h3 class='card-title'>Add Color Code Form</h3>
        </div>
        <!-- /.card-header -->
        <!-- general form elements -->
        <!-- form start -->
        <form id='color_code_form' method='POST' action="{{ route('admin.color-codes.store') }}">
            @csrf
            <!-- /.card-body -->
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
    <script>

        $('#color').on('input', function () {
    $('#colorText').val($(this).val());
});
    </script>
      @endpush

</x-layout>
