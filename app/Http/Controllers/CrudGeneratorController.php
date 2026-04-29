<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;



class CrudGeneratorController extends Controller
{

    public function index()
    {
        return view('admin.crud-generator.index');
    }

    public function createFileFromCode($phpCode, $filePath, $key = null)
    {
        if ($key == 'migrataion') {
            $filePath = database_path($filePath);
        } else if ($key == "blade") {
            $filePath = resource_path($filePath);

            $directoryPath = dirname($filePath);

            if (!is_dir($directoryPath)) {
                if (mkdir($directoryPath, 0755, true)) {
                    echo 'Directory created successfully.';
                } else {
                    echo 'Failed to create the directory.';
                }
            } else {
                echo 'Directory already exists.';
            }
        } else {
            $filePath = app_path($filePath);
        }
        if (file_put_contents($filePath, $phpCode) !== false) {
            return "PHP code successfully created";
        } else {
            return "Error writing PHP code to the file.";
        }
    }

    public function crudGenerator()
    {
        $model = request()->input('model');
        $database_singular = request()->input('database_singular');
        $database_plural = request()->input('database_plural');
        $plular_title = request()->input('plular_title');
        $singular_title = request()->input('singular_title');
        $database_plular_with_minus = request()->input('database_plular_with_minus');
        $database_singular_with_minus = request()->input('database_singular_with_minus');

        $res = request()->all();
        if ($res) {
            $attribute =  $res['attribute'];
            $label =  $res['label'];
            $dataType =  $res['dataType'];
            $required =  $res['required'];
            $inputType =  $res['inputType'];
            $referred_table =  $res['referred_table'];



            $request_code = "<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Store" . $model . "Request extends FormRequest
{
    /**
     * Determine if the row is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

        return [
            ";

            foreach ($attribute as  $key => $value) {

                //below are attributes
                $request_code .= "'" . $value . "' => '" . "$required[$key]" . "'," . "\n";
            }


            $request_code .= "
            ];
        }
    }";
            $update_request_code = "<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Update" . $model . "Request extends FormRequest
{
    /**
     * Determine if the row is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

        return [
            ";

            foreach ($attribute as  $key => $value) {

                //below are attributes
                $update_request_code .= "'" . $value . "' => '" . "" . "'," . "\n";
            }


            $update_request_code .= "
        ];
    }
}";
            //create request validation
            $request_code = str_replace("{dollar}", "$", $request_code);

            $update_request_code = str_replace("{dollar}", "$", $update_request_code);

            $modelName = ucfirst($model);

            $file = "Store" . $modelName . "Request";
            $filePath = "Http/Requests/{$file}.php";

            $this->createFileFromCode($request_code, $filePath, null);

            $this->createFileFromCode($update_request_code, str_replace("Store", "Update", $filePath), null);


            // dd("done!!");


            $controller_code = "<?php \nnamespace App\Http\Controllers;
use App\DataTables\\" . $model . "DataTable;
use App\Models\\" . $model . ";
use App\Http\Requests\Store" . $model . "Request;
use App\Http\Requests\Update" . $model . "Request;
use App\Models\User;
use App\Traits\ModelAuthorizable;
use Illuminate\Support\Facades\DB;


class " . $model . "Controller extends Controller
{
    use ModelAuthorizable;
    /**
     * Display a listing of the resource.
     */
    public function index(" . $model . "DataTable {dollar}dataTable)
    {";

            $referred_table = array_filter($referred_table);
            if ($referred_table) {
                foreach ($referred_table as $db) {
                    $controller_code .= "{dollar}" . "$db" . "= DB::table('" . $db . "')->get();";
                }
                $controller_code .=
                    "
                return {dollar}dataTable->render('admin." . $database_plular_with_minus . ".index',compact(";

                foreach ($referred_table as $db) {
                    $controller_code .= "'" . "$db" . "',";
                }
                $controller_code .= "));";
            } else {
                $controller_code .= "
            return {dollar}dataTable->render('admin." . $database_plular_with_minus . ".index');";
            }

            $controller_code .= "
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {";

            $referred_table = array_filter($referred_table);
            if ($referred_table) {
                foreach ($referred_table as $db) {
                    $controller_code .= "{dollar}" . "$db" . "= DB::table('" . $db . "')->get();";
                }
                $controller_code .=
                    "
        return view('admin." . $database_plular_with_minus . ".new',compact(";

                foreach ($referred_table as $db) {
                    $controller_code .= "'" . "$db" . "',";
                }
                $controller_code .= "));";
            } else {

                $controller_code .= " return view('admin." . $database_plular_with_minus . ".new');";
            }




            $controller_code .= "
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Store" . $model . "Request {dollar}request)
    {

        {dollar}$database_singular = " . $model . "::create({dollar}request->validated());

        return redirect()->route('admin." . $database_plular_with_minus . ".index')->with('success_create', ' $database_singular added!');
    }

    /**
     * Display the specified resource.
     */
    public function show($model {dollar}$database_singular)
    {
        if (request()->ajax()) {
            {dollar}response = array();
            {dollar}response['success'] = 1;
            {dollar}response['$database_singular'] = {dollar}$database_singular;
            return response()->json({dollar}response);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($model {dollar}$database_singular)
    {
        if (request()->ajax()) {
            {dollar}response = array();
            {dollar}response['success'] = 1;
            {dollar}response['$database_singular'] = {dollar}$database_singular;
            return response()->json({dollar}response);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update" . $model . "Request {dollar}request, $model {dollar}$database_singular)
    {

        {dollar}" . $database_singular . "->update({dollar}request->validated());

        return response()->json(array('success' => true), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($model {dollar}$database_singular)
    {
        if (!{dollar}" . $database_singular . "->exists()) {
            return redirect()->route('admin." . $database_plular_with_minus . ".index')->with('error', 'Unautorized!');
        }
        {dollar}" . $database_singular . "->delete();
        return response()->json(array('success' => true), 200);
    }
}

        ";



            //create controller
            $controller_code = str_replace("{dollar}", "$", $controller_code);
            $file =  $model . "Controller";
            $filePath = "Http/Controllers/{$file}.php";
            $this->createFileFromCode($controller_code, $filePath, null);




            $dataTable_code = "<?php

namespace App\DataTables;

use App\Constants\Constants;
use App\Models\\" . $model . ";
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

use function Termwind\\render;

class " . $model . "DataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder {dollar}query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder {dollar}query): EloquentDataTable
    {
        {dollar}index_column = 0;
        return (new EloquentDataTable({dollar}query))
            ->addColumn('no', function () use (&{dollar}index_column) {
                return ++{dollar}index_column;
            })
            ->addColumn('action', function ({dollar}" . $database_singular . ") {
                return view('components.action-buttons', [
                    'row_id' => {dollar}" . $database_singular . "->id,
                    'show'=>true,
                    'permission_delete'=>'" . $database_singular_with_minus . ": delete',
                    'permission_edit'=>'" . $database_singular_with_minus . ": edit',
                    'permission_view'=>'" . $database_singular_with_minus . ": view',
                ]);
            })
            ->rawColumns(['no', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\\" . $model . " {dollar}model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(" . $model . " {dollar}model): QueryBuilder
    {
        // return {dollar}model->newQuery();
        return {dollar}model::select([
            'id',
            'created_at' ,\n";
            foreach ($attribute as   $value) {

                //below are attributes
                $dataTable_code .= "'" . $value . "'" . ",\n";
            }
            $dataTable_code .= "
            
        ]);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return {dollar}this->builder()
            ->setTableId('" . "$database_plular_with_minus" . "-table')
            ->columns({dollar}this->getColumns())
            ->orderBy(3)
            ->minifiedAjax()
            ->selectStyleSingle()
            ->dom(\"'<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6'B>
                           <'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>>
                           <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>'\")
            ->responsive(true)
            ->processing(true)
            ->autoWidth(false)
            ->buttons(
                [
                    [
                        'extend' => 'csvHtml5',
                        'text' => 'CSV',
                        'exportOptions' => [
                            'columns' => ':visible',
                        ],
                    ],
                    [
                        'extend' => 'excelHtml5',
                        'text' => 'Excel',
                        'exportOptions' => [
                            'columns' => ':visible',
                        ],
                    ],
                    [
                        'extend' => 'pdfHtml5',
                        'text' => 'PDF',
                        'exportOptions' => [
                            'columns' => ':visible',
                        ],
                    ],

                    [
                        'extend' => 'print',
                        'text' => 'Print',
                        'exportOptions' => [
                            'columns' => ':visible',
                        ],
                    ],
                    'colvis',
                ]
            )
            ->lengthMenu(Constants::PAGE_NUMBER()) // Customize the options here
            ->language([
                'lengthMenu' => '_MENU_ records per page', // Customize the attribute
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::computed('no')->title('No')
                ->exportable(false)
                ->addClass('text-center')
                ->orderable(false), \n";
            foreach ($attribute as   $value) {

                $dataTable_code .= "Column::make('" . "$value" . "'),\n";
            }
            $dataTable_code .= "
            Column::computed('action')
                ->exportable(false)
                ->printable(true)
                ->addClass('text-center')
                ->orderable(false),
            Column::make('created_at')->visible(false)

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return \"" . $database_plural . "\". date('YmdHis');
    }
}
";

            $dataTable_code = str_replace("{dollar}", "$", $dataTable_code);
            $file =  $model . "DataTable";
            $filePath = "DataTables/{$file}.php";
            $this->createFileFromCode($dataTable_code, $filePath, null);



            $model_code = "<?php

        namespace App\Models;
        
        use App\Traits\CreatedUpdatedBy;
        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Model;
        use Illuminate\Database\Eloquent\SoftDeletes;
        use Spatie\Activitylog\Traits\LogsActivity;
        use Spatie\Activitylog\LogOptions;
        class $model extends Model
        {
            use HasFactory, SoftDeletes, LogsActivity,CreatedUpdatedBy;
            protected {dollar}guarded = [
                'id',
            ];
            public function getActivitylogOptions(): LogOptions
            {
                return LogOptions::defaults()
                ->logOnly([";
            foreach ($attribute as   $value) {
                $model_code .= "'" . "$value" . "',\n";
            }
            $model_code .= "
                ]);
            }
        }
        ";

            $model_code = str_replace("{dollar}", "$", $model_code);
            $file =  $model;
            $filePath = "Models/{$file}.php";
            $this->createFileFromCode($model_code, $filePath, null);


            $migration_code = "<?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;
        
        return new class extends Migration
        {
            /**
             * Run the migrations.
             */
            public function up(): void
            {
                Schema::create(" . "'" . $database_plural . "'," . " function (Blueprint {dollar}table) {
                    {dollar}table->id();\n";

            foreach ($attribute as $key => $value) {
                $migration_code .=   "{dollar}table->" . "$dataType[$key]" . "('" . "$value" . "');\n";
            }

            $migration_code .= " 
                    {dollar}table->foreignId('created_by')->nullable()->constrained('users');
                    {dollar}table->foreignId('updated_by')->nullable()->constrained('users');
                    {dollar}table->softDeletes();
                    {dollar}table->timestamps();
                });
            }
        
            /**
             * Reverse the migrations.
             */
            public function down(): void
            {
                Schema::dropIfExists('" . "$database_plural" . "');
            }
        };
        ";
            $migration_code = str_replace("{dollar}", "$", $migration_code);
            $file =  Carbon::now()->format('Y_m_d_Hi') . "_create_" . $database_plural . "_table";
            $filePath = "migrations/{$file}.php";
            $this->createFileFromCode($migration_code, $filePath, 'migrataion');


            $index_blade_code = "<x-layout>
        <!-- Content Header (Page header) -->
        <x-breadcrump title=" . "'" . "$plular_title" . " List' parent='" . "$plular_title" . "'" . " child='List' />
        <!-- /.content-header -->
    
        <!-- /.content-Main -->
        <div class='card'>
            <div class='card-header'>
                <div class='col'>
                    <div style='display: flex; justify-content:flex-end'>
                        <div>
                        @can('" . "$database_singular_with_minus" . ": create')
                        <a href=" . '"' . "{{route('admin." . "$database_plular_with_minus" . ".create') }}" . '"' . ">
                            <button type='button' class='btn btn-primary'>Add New " . "$singular_title" . "</button>
                        </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class='card-body'>
                {{ {dollar}dataTable->table(['class' => 'table table-bordered table-striped']) }}
            </div>
    
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /#updateModal -->
        <x-partials." . "$database_singular" . "_modal";

            $referred_table = array_filter($referred_table);
            if ($referred_table) {
                foreach ($referred_table as $db) {
                    $index_blade_code .= ":" . "$db" . "=" . '"' . "{dollar}" . "$db" . '"';
                }
            }

            $index_blade_code .= " />
        <x-show-modals." . "$database_singular" . "_show_modal />
        <!-- /#updateModal -->
        <!-- /.content -->
        <!-- Custom Js contents -->
        @push('scripts')
        {{ {dollar}dataTable->scripts(attributes: ['type' => 'module']) }}
        <script>";

            if (in_array('checkbox', $inputType)) {
                $index_blade_code .= "{dollar}('input[data-bootstrap-switch]').each(function() {
        {dollar}(this).bootstrapSwitch('state', {dollar}(this).prop('checked'));
    });";
            }

            "</script>

<script>";
            $referred_table = array_filter($referred_table);
            if ($referred_table) {
                foreach ($referred_table as $db) {
                    $index_blade_code .= "{dollar}('." . "$db" . "_select2').select2();";
                }
            }


            if (in_array('date', $inputType)) {
                $arrayLength = count($inputType);
                $index_blade_code .= " 
                $(function() {";
                for ($i = 0; $i < $arrayLength; $i++) {
                    $index_blade_code .= " $('#" . "$attribute[$i]" . "').datetimepicker({
                            pickTime: false
                        });";
                }

                $index_blade_code .= "  });";
            }

            $index_blade_code .= "</script>
        <script>


            //delete row
            function delete_row(element, row_id) {
                var url = " . '"' . "{{ route('admin." . "$database_plular_with_minus" . ".destroy', ':id') }}" . '"' . ";
                url = url.replace(':id', row_id);
                console.log(url);
    
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mx-1',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
    
                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: " . '"' . "You won't be able to revert this!" . '"' . ",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: url,
                            data: {
                                row_id: row_id,
                            },
                            dataType: 'json',
                            success: function(data) {
                                console.log(data);
                                if (data.success) {
                                    window.LaravelDataTables['" . "$database_plular_with_minus" . "-table'].ajax.reload();
                                }
                            },
                            error: function(error) {
                                if (error.status ==
                                    422) { // when status code is 422, it's a validation issue
    
                                }
                                console.log('debug error here');
                            }
                        })
                        swalWithBootstrapButtons.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                        )
                    }
                })
            }
    
            if (@json(session('success_create'))) {
    
                toastr.success('You have successfuly added a new " . "$singular_title" . "')
            }
    
            $(document).ready(function() {
                // Update record popup
                $('#" . "$database_plular_with_minus" . "-table').on('click', '#update_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = " . '"' . "{{ route('admin." . "$database_plular_with_minus" . ".edit', ':id') }}" . '"' . ";
                    url = url.replace(':id', row_id);
    
                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            console.log('success');
                            var $database_singular = response." . "$database_singular" . "
                            if (response.success == 1) {
                                console.log($database_singular);
                                $('#" . "$database_singular" . "_id').val(" . "$database_singular" . ".id);\n";

            foreach ($attribute as $value) {

                $index_blade_code .=  "$('#" . "$value" . "').val(" . "$database_singular" . "." . "$value" . ");\n";
            }
            $index_blade_code .= " $('#update_modal').modal('show');
    
                            } else {
                                alert('Invalid ID.');
                            }
                        }
                    });
                });

                //show
                $('#" . "$database_plular_with_minus" . "-table').on('click', '#show_row', function() {
                    var row_id = $(this).data('row_id');
                    var url = " . '"' . "{{ route('admin." . "$database_plular_with_minus" . ".show', ':id') }}" . '"' . ";
                    url = url.replace(':id', row_id);
    
                    // AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            console.log('success');
                            var $database_singular = response." . "$database_singular" . "
                            if (response.success == 1) {
                                console.log($database_singular);
                                $('#" . "$database_singular" . "_id').val(" . "$database_singular" . ".id);";

            foreach ($attribute as $value) {

                $index_blade_code .=  "$('#show_modal #" . "$value" . "').html(" . "$database_singular" . "." . "$value" . ");\n";
            }
            $index_blade_code .= " $('#show_modal').modal('show');
    
                            } else {
                                alert('Invalid ID.');
                            }
                        }
                    });
                });
            });
    
    
            $('#" . "$database_singular" . "_update_form').on('submit', function(e) {
                e.preventDefault();
                form_data = $(this).serialize();
                row_id = $('#" . "$database_singular" . "_id', $(this)).val()
                console.log(row_id);

                var url = " . '"' . "{{ route('admin." . "$database_plular_with_minus" . ".update', ':id') }}" . '"' . ";
                url = url.replace(':id', row_id);
    
                // AJAX request
                $.ajax({
                    url: url,
                    type: 'PATCH',
                    data: form_data,
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            console.log('111111111111111');
                            console.log(data);
                            console.log('2222222222222222');
                            $('#update_modal').modal('toggle');
                            window.LaravelDataTables['" . "$database_plular_with_minus" . "-table'].ajax.reload();
                            toastr.success('You have successfuly updated a " . "$singular_title" . ".')
                        }
                    },
                    error: function(error) {
                        console.log('error');
                    }
                });
    
            });
        </script>
        @endpush
        <!-- Custom Js contents -->
    
    </x-layout>
    ";

            $index_blade_code = str_replace("{dollar}", "$", $index_blade_code);
            $filePath = "views/admin/{$database_plular_with_minus}/index.blade.php";
            $this->createFileFromCode($index_blade_code, $filePath, 'blade');

            $new_blade_code = "<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title='Add New " . "$singular_title" . "' parent='" . "$singular_title" . "' child='Add New " . "$singular_title" . "' />
    <!-- /.content-header -->

    <!-- Main content -->
    <div class='card card-info'>
        <div class='card-header'>
            <h3 class='card-title'>Add " . "$singular_title" . " Form</h3>
        </div>
        <!-- /.card-header -->
        <!-- general form elements -->
        <!-- form start -->
        <form id='" . "$database_singular" . "_form' method='POST' action=" . '"' . "{{ route('admin." . "$database_plular_with_minus" . ".store') }}" . '"' . ">
            @csrf
            <!-- /.card-body -->
            <!-- row -->
            <div class='card-body row'>
                <!-- left column -->
                <div class='col-md-6'>";

            foreach ($attribute as $key => $value) {


                if ($inputType[$key] == 'text') {
                    $new_blade_code .=

                        "<div class='form-group'>
                         <label class='col-12'>" . "$label[$key]" . "</label>
                             <input type='" . "$inputType[$key]" . "' class='form-control' id='" . "$value" . "' name='" . "$value" . "' placeholder='Enter " . "$label[$key]" . "'>
                         </div>";
                }
                 else if ($inputType[$key] == 'textarea') {

                    $new_blade_code .=

                        "<div class='form-group'>
                        <label class='col-12 '>" . "$label[$key]" . "</label>
                        <textarea rows='4' cols='30' class='form-control' id= " . "$value" . " name='" . "$value" . "'></textarea>
                        </div>";
                } else if ($inputType[$key] == 'checkbox') {

                    $new_blade_code .= "<div class='form-group'>
                <label for='status'>" . "$label[$key]" . "</label>
                        <div class='px-3'>
                            <input type='" . "$inputType[$key]" . "' name='" . "$value" . "' id='" . "$value" . "'  data-bootstrap-switch data-off-color='danger' data-on-color='success'>
                        </div>
                </div>";
                } else if ($inputType[$key] == 'select') {

                    $new_blade_code .= "   <div class='form-group'>
                    <label for='" . "$referred_table[$key]" . "'>" . "$label[$key]" . "</label>
                    <select name='" . "$attribute[$key]" . "' class='" . "$referred_table[$key]" . "_select2 select2 form-control' id='" . "$attribute[$key]" . "' data-dropdown-css-class='select2-blue'>
                        <option value='none' selected disabled>Select a " . "$referred_table[$key]" . "</option>
                        @foreach ({dollar}" . "$referred_table[$key]" . " " . "as" . " " . " {dollar}" . "$database_singular" . ")
                        <option value='{{" . "{dollar}" . $database_singular . "->id }}'>

                        ";
                    if ($referred_table[$key] == "users") {
                        $new_blade_code .= " {{" . "{dollar}" . $database_singular . "->first_name }} {{" . "{dollar}" . $database_singular . "->middle_name }}";
                    }
                    /* else if($referred_table[$key]=="others")
                        {
                            $new_blade_code .="{{" . "{dollar}" . $database_singular . "->title }}";
                        }*/ else {
                        $new_blade_code .= "{{" . "{dollar}" . $database_singular . "->name }}";
                    }

                    $new_blade_code .= "                        
                        </option>
                        @endforeach
                    </select>
                </div>";
                } else if ($inputType[$key] == 'file') {

                    $new_blade_code .=

                        "<div class='form-group'>
                     <label class='col-12 '>" . "$label[$key]" . "</label>
                         <input type='" . "$inputType[$key]" . "' id='" . "$value" . "' name='" . "$value" . "'>
                     </div>";
                } else if ($inputType[$key] == 'date') {

                    $new_blade_code .= "<div class='form-group'>
                        <label>" . "$label[$key]" . "</label>
                        <div class='input-append input-group'>
                            <div class='input-group-append' data-target='#" . "$value" . "'>
                                <div class='input-group-text'><i class='fa fa-calendar'></i></div>
                            </div>
                            <input id='" . "$value" . "' name='" . "$value" . "' class='form-control' data-provide='datepicker' data-date-format='yyyy-mm-dd' type='text'>

                        </div>
                    </div>";
                }
            }


            $new_blade_code .= "</div>
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
    <script>";

            if (in_array('checkbox', $inputType)) {
                $new_blade_code .= "{dollar}('input[data-bootstrap-switch]').each(function() {
            {dollar}(this).bootstrapSwitch('state', {dollar}(this).prop('checked'));
        });";
            }

            "</script>
    
    <script>";

            if (in_array('date', $inputType)) {
                $arrayLength = count($inputType);
                $new_blade_code .= " 
        $(function() {";
                for ($i = 0; $i < $arrayLength; $i++) {
                    $new_blade_code .= " $('#" . "$attribute[$i]" . "').datetimepicker({
                    pickTime: false
                });";
                }

                $new_blade_code .= "  });";
            }
            $referred_table = array_filter($referred_table);
            if ($referred_table) {
                foreach ($referred_table as $db) {
                    $new_blade_code .= "{dollar}('." . "$db" . "_select2').select2();";
                }
            }
            $new_blade_code .= "</script>
      @endpush

</x-layout>
";



            $new_blade_code = str_replace("{dollar}", "$", $new_blade_code);
            $filePath = "views/admin/{$database_plular_with_minus}/new.blade.php";
            $this->createFileFromCode($new_blade_code, $filePath, 'blade');




            $edit_modal_code = "@props([";

            $referred_table = array_filter($referred_table);
            if ($referred_table) {
                foreach ($referred_table as $db) {
                    $edit_modal_code .= "'" . "$db" . "',";
                }
            }
            $edit_modal_code .= "])

    <!-- /.modal -->
    <div class='modal fade' id='update_modal'>
        <div class='modal-dialog modal-lg'>
    
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Update " . "$singular_title" . " Detail</h4>
                    <button type='button' class='close' data-dismiss='modal' aria-attribute='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <form id='" . "$database_singular" . "_update_form'>
                    @csrf
                    <div class='modal-body'>
                        <!-- /.card-body -->
                        <!-- row -->
                        <!-- row -->
            <div class='card-body row'>
                <!-- left column -->
                <div class='col-md-6'>";

            foreach ($attribute as $key => $value) {

                if ($inputType[$key] == 'text') {
                    $edit_modal_code .=

                        "<div class='form-group'>
                         <label class='col-12'>" . "$label[$key]" . "</label>
                             <input type='" . "$inputType[$key]" . "' class='form-control' id='" . "$value" . "' name='" . "$value" . "' placeholder='Enter " . "$label[$key]" . "'>
                         </div>";
                } else if ($inputType[$key] == 'textarea') {

                    $edit_modal_code .=

                        "<div class='form-group'>
                <label class='col-12'>" . "$label[$key]" . "</label>
                <textarea rows='4' cols='30' class='form-control' id= " . "$value" . " name='" . "$value" . "'>
                   </textarea>
                </div>";
                } else if ($inputType[$key] == 'checkbox') {

                    $edit_modal_code .= "<div class='form-group'>
                <label for='status'>" . "$label[$key]" . "</label>
                        <div class='px-3'>
                            <input type='" . "$inputType[$key]" . "' name='" . "$value" . "' id='" . "$value" . "'  data-bootstrap-switch data-off-color='danger' data-on-color='success'>
                        </div>
                </div>";
                } else if ($inputType[$key] == 'select') {

                    $edit_modal_code .= "   <div class='form-group'>
                    <label for='" . "$referred_table[$key]" . "'>" . "$label[$key]" . "</label>
                    <select name='" . "$attribute[$key]" . "' class='" . "$referred_table[$key]" . "_select2 select2 form-control' id='" . "$attribute[$key]" . "' data-dropdown-css-class='select2-blue'>
                        <option value='none' selected disabled>Select a " . "$referred_table[$key]" . "</option>
                        @foreach ({dollar}" . "$referred_table[$key]" . " " . "as" . " " . " {dollar}" . "$database_singular" . ")
                        <option value='{{" . "{dollar}" . $database_singular . "->id }}'>

                        ";
                    if ($referred_table[$key] == "users") {
                        $edit_modal_code .= " {{" . "{dollar}" . $database_singular . "->first_name }} {{" . "{dollar}" . $database_singular . "->middle_name }}";
                    }
                    /* else if($referred_table[$key]=="others")
                        {
                            $edit_modal_code .="{{" . "{dollar}" . $database_singular . "->title }}";
                        }*/ else {
                        $edit_modal_code .= "{{" . "{dollar}" . $database_singular . "->name }}";
                    }

                    $edit_modal_code .= "                        
                        </option>
                        @endforeach
                    </select>
                </div>";
                } else if ($inputType[$key] == 'file') {

                    $edit_modal_code .=

                        "<div class='form-group'>
                     <label class='col-12 '>" . "$label[$key]" . "</label>
                         <input type='" . "$inputType[$key]" . "'  id='" . "$value" . "' name='" . "$value" . "' >
                     </div>";
                } else if ($inputType[$key] == 'date') {

                    $edit_modal_code .= "<div class='form-group'>
                        <label>" . "$label[$key]" . "</label>
                        <div class='input-append input-group'>
                            <div class='input-group-append' data-target='#" . "$value" . "'>
                                <div class='input-group-text'><i class='fa fa-calendar'></i></div>
                            </div>
                            <input id='" . "$value" . "' name='" . "$value" . "' class='form-control' data-provide='datepicker' data-date-format='yyyy-mm-dd' type='text'>

                        </div>
                    </div>";
                }
            }


            $edit_modal_code .= "</div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
                    </div>
                    <div class='modal-footer justify-content-between'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        <input type='hidden' name='" . "$database_singular" . "_id' id='" . "$database_singular" . "_id'>
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
    ";


            $edit_modal_code = str_replace("{dollar}", "$", $edit_modal_code);
            $filePath = "views/components/partials/{$database_singular}_modal.blade.php";
            $this->createFileFromCode($edit_modal_code, $filePath, 'blade');

            $show_modal_code = "
<!-- /.modal -->
<div class='modal fade' id='show_modal'>
    <div class='modal-dialog modal-xl'>

        <div class='modal-content'>
            <div class='modal-header'>
                <h4 class='modal-title'>" . "$singular_title" . " Detail</h4>
                <button type='button' class='close' data-dismiss='modal' aria-attribute='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <div class='modal-body'>
                <!-- /.card-body -->
                <!-- row -->
                <div class='card-body'>";
            foreach ($attribute as $key => $value) {
                $show_modal_code .= "<div class='form-group row'>
                        <label class='col-sm-2 col-form-label'>" . "$label[$key]" . "</label>
                        <div class='col-sm-10'>
                            <p name='" . "$value" . "' id='" . "$value" . "'></p>
                        </div>
                    </div>";
            }
            $show_modal_code .= "</div>
                <!-- /.row -->
                <!-- /.card-body -->
            </div>
            <div class='modal-footer'>
                <button type='submit' class='btn btn-info show-button' data-dismiss='modal'>Close</button>
            </div>
            <!-- /#user_form -->

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
";

            $show_modal_code = str_replace("{dollar}", "$", $show_modal_code);
            $filePath = "views/components/show-modals/{$database_singular}_show_modal.blade.php";
            $this->createFileFromCode($show_modal_code, $filePath, 'blade');
        }

        return redirect()->route('admin.crud-generator.index');
    }
}
