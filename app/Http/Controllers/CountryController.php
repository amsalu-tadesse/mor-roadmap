<?php 
namespace App\Http\Controllers;
use App\DataTables\CountryDataTable;
use App\Models\Country;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\User;
use App\Traits\ModelAuthorizable;
use Illuminate\Support\Facades\DB;


class CountryController extends Controller
{
    use ModelAuthorizable;
    /**
     * Display a listing of the resource.
     */
    public function index(CountryDataTable $dataTable)
    {
            return $dataTable->render('admin.countries.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { return view('admin.countries.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCountryRequest $request)
    {

        $country = Country::create($request->validated());

        return redirect()->route('admin.countries.index')->with('success_create', ' country added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        if (request()->ajax()) {
            $response = array();
            $response['success'] = 1;
            $response['country'] = $country;
            return response()->json($response);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        if (request()->ajax()) {
            $response = array();
            $response['success'] = 1;
            $response['country'] = $country;
            return response()->json($response);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {

        $country->update($request->validated());

        return response()->json(array('success' => true), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        if (!$country->exists()) {
            return redirect()->route('admin.countries.index')->with('error', 'Unautorized!');
        }
        $country->delete();
        return response()->json(array('success' => true), 200);
    }
}

        