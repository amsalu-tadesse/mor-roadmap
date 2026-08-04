<?php 
namespace App\Http\Controllers;
use App\DataTables\ColorCodeDataTable;
use App\Models\ColorCode;
use App\Http\Requests\StoreColorCodeRequest;
use App\Http\Requests\UpdateColorCodeRequest;
use App\Models\User;
use App\Traits\ModelAuthorizable;
use Illuminate\Support\Facades\DB;


class ColorCodeController extends Controller
{
    use ModelAuthorizable;
    /**
     * Display a listing of the resource.
     */
    public function index(ColorCodeDataTable $dataTable)
    {
            return $dataTable->render('admin.color-codes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { return view('admin.color-codes.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreColorCodeRequest $request)
    {

        $color_code = ColorCode::create($request->validated());

        return redirect()->route('admin.color-codes.index')->with('success_create', ' color_code added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ColorCode $color_code)
    {
        if (request()->ajax()) {
            $response = array();
            $response['success'] = 1;
            $response['color_code'] = $color_code;
            return response()->json($response);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ColorCode $color_code)
    {
        if (request()->ajax()) {
            $response = array();
            $response['success'] = 1;
            $response['color_code'] = $color_code;
            return response()->json($response);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateColorCodeRequest $request, ColorCode $color_code)
    {

        $color_code->update($request->validated());

        return response()->json(array('success' => true), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ColorCode $color_code)
    {
        if (!$color_code->exists()) {
            return redirect()->route('admin.color-codes.index')->with('error', 'Unautorized!');
        }
        $color_code->delete();
        return response()->json(array('success' => true), 200);
    }
}

        