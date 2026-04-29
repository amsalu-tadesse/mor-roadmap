<?php 
namespace App\Http\Controllers;
use App\DataTables\LoginAttemptDataTable;
use App\Models\LoginAttempt;
use App\Http\Requests\StoreLoginAttemptRequest;
use App\Http\Requests\UpdateLoginAttemptRequest;
use App\Models\User;
use App\Traits\ModelAuthorizable;
use Illuminate\Support\Facades\DB;


class LoginAttemptController extends Controller
{
    use ModelAuthorizable;
    /**
     * Display a listing of the resource.
     */
    public function index(LoginAttemptDataTable $dataTable)
    {$users= DB::table('users')->get();
                return $dataTable->render('admin.login-attempts.index',compact('users',));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {$users= DB::table('users')->get();
        return view('admin.login-attempts.new',compact('users',));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoginAttemptRequest $request)
    {

        $login_attempt = LoginAttempt::create($request->validated());

        return redirect()->route('admin.login-attempts.index')->with('success_create', ' login_attempt added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(LoginAttempt $login_attempt)
    {
        if (request()->ajax()) {
            $response = array();
            $response['success'] = 1;
            $response['login_attempt'] = $login_attempt;
            return response()->json($response);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoginAttempt $login_attempt)
    {
        if (request()->ajax()) {
            $response = array();
            $response['success'] = 1;
            $response['login_attempt'] = $login_attempt;
            return response()->json($response);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoginAttemptRequest $request, LoginAttempt $login_attempt)
    {

        $login_attempt->update($request->validated());

        return response()->json(array('success' => true), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoginAttempt $login_attempt)
    {
        if (!$login_attempt->exists()) {
            return redirect()->route('admin.login-attempts.index')->with('error', 'Unautorized!');
        }
        $login_attempt->delete();
        return response()->json(array('success' => true), 200);
    }
}

        