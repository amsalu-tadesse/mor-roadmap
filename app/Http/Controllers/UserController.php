<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;

use App\Models\Email;
use App\Mail\SendMail;
use App\Models\Profile;
use Illuminate\Support\Str;
use App\Constants\Constants;

use Illuminate\Http\Request;
use App\Services\EmailService;
use App\DataTables\UsersDataTable;
use Spatie\Permission\Models\Role;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

use App\Traits\ModelAuthorizable;
use App\Models\Notification;
use App\Models\Organization;
use Carbon\Carbon;

class UserController extends Controller
{
    use ModelAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {

        $roles = Role::select('id', 'name')->get();
        $organizations = Organization::all();

        return $dataTable->render('admin.users.index', compact('roles', 'organizations'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $organizations = Organization::all();
        return view('admin.users.new', compact('roles', 'organizations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, EmailService $emailService)
    {

        // dd(request()->all());
        $validated_data = $this->getValidatedData($request);


        if ($request->has('user_roles')) {
            $user_roles = $validated_data['user_roles'];
            unset($validated_data['user_roles']);
        }
        // dd($validated_data);

        $user = User::create($validated_data);
        Notification::create([
            "title" => "Account is created succesfully {$user->first_name} {$user->last_name}",
            "date" => Carbon::now(),
            "is_seen" => 0
        ]);
        $password = Str::random(8);
        $user->password = $password;
        $user->save();

        foreach ($user_roles ?? [] as $role) {
            $role = Role::findById($role);
            $user->assignRole($role);
        }

        //Mailing
        $messageObj = Email::where('code', 'email:on_user_registration')->first();
        if ($messageObj?->status == 1) {
            $body = $messageObj->body;
            $link = Constants::DOMAIN . '/login';
            $body = str_ireplace("{user}", $user?->first_name, $body);
            $body = str_ireplace("{password}", $password, $body);
            $body = str_ireplace("{link}", $link, $body);
            $message['title'] = $messageObj->subject;
            $message['body'] = $body;

            try {
                //$emailService->sendMail([$request->input('email')], [], $message);
                dispatch(new \App\Jobs\SendEmailJob([$request->input('email')], [], $message));
            } catch (Exception $ex) {
                //continue.
                dd("not able to send email.", $ex);
            }
        }



        return redirect()->route('admin.users.index')->with('success_create', 'Users added!');
    }

    public function getValidatedData($request)
    {
        $validated_data = $request->validated();



        $is_superadmin = 0;
        $status = 0;
        if ($request->has('is_superadmin')) $is_superadmin = 1;

        if ($request->has('status')) $status = 1;

        $validated_data['status'] = $status;
        $validated_data['is_superadmin'] = $is_superadmin;

        return $validated_data;
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {

        // dd($user->password_changed);
        if (request()->ajax()) {
            $response = array();

            $creator = User::find($user->created_by);
            $fName = $creator->first_name;
            $mName = $creator->middle_name;
            $lName = $creator->last_name;

            $getCreatedBy = $fName . ' ' . $mName . ' ' . $lName;
            // $requestuser->rol
            $response['success'] = 1;
            $response['getCreatedBy'] = $getCreatedBy;

            $response['user'] = $user;
            return response()->json($response);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (request()->ajax()) {
            $response = array();
            if (!empty($user)) {
                $organization = $user->organization;
                $response['organization'] = $organization;
                $response['organization_id'] = $user->organization_id;
                $response['row_id'] = $user->id;
                $response['first_name'] = $user->first_name;
                $response['middle_name'] = $user->middle_name;
                $response['last_name'] = $user->last_name;
                $response['email'] = $user->email;
                $response['mobile'] = $user->mobile;
                $response['status'] = $user->status;
                $response['is_superadmin'] = $user->is_superadmin;
                $response['user_roles'] = $user->roles->pluck('id', 'name');
                $response['success'] = 1;
            } else {
                $response['success'] = 0;
            }

            return response()->json($response);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated_data = $this->getValidatedData($request);

        unset($validated_data['row_id']);

        if ($request->has('user_roles')) {
            $user_roles = $validated_data['user_roles'];
            unset($validated_data['user_roles']);
        }

        $user->update($validated_data);
        $user->save();
        $user->syncRoles([]); //remove all user roles

        foreach ($user_roles ?? [] as $role) {
            $role = Role::findById($role);
            $user->assignRole($role);
        }
        // $user->update($request->validated());
        // return view('admin.users.edit');

        return response()->json(array("success" => true), 200);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!$user->exists()) {
            return redirect()->route('admin.users.index')->with('error', 'Unautorized!');
        }
        Notification::create([
            "title" => "Account is deletd succesfully for {$user->first_name} {$user->last_name}",
            "date" => Carbon::now(),
            "is_seen" => 0
        ]);
        $user->delete();

        return response()->json(array("success" => true), 200);
    }

    public function changeProfile(Request $request)
    {
        $current_row_id = auth()->user()->id;
        $users = User::find(auth()->user()->id);

        $this->validate($request, [
            'position' => 'nullable',
            'education' => 'nullable',
            'profession' => 'nullable',
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',


        ]);


        if ($users) {
            $users->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,

            ]);
        }

        $profile = Profile::where('row_id', $current_row_id)->first();
        // $imagePath = null; // Initialize image path variable


        if ($profile) {
            if ($request->hasFile('profile_image')) {
                $profile_image_path = $request->file('profile_image')->store('profile_images', 'public');
                $profile->update([
                    'position' => $request->position,
                    'education' => $request->education,
                    'profession' => $request->profession,
                    'profile_image' => $profile_image_path,
                ]);
            } else {
                $profile->update([
                    'position' => $request->position,
                    'education' => $request->education,
                    'profession' => $request->profession,
                ]);
            }
        } else {
            if ($request->hasFile('profile_image')) {
                $profile_image_path = $request->file('profile_image')->store('profile_images', 'public');
                $profile = Profile::create([
                    'position' => $request->position,
                    'education' => $request->education,
                    'profession' => $request->profession,
                    'row_id' => $current_row_id,
                    'profile_image' => $profile_image_path,
                ]);
            } else {
                $profile = Profile::create([
                    'position' => $request->position,
                    'education' => $request->education,
                    'profession' => $request->profession,
                    'row_id' => $current_row_id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
    public function profile()
    {
        $current_row_id = auth()->user()->id;

        $profile = Profile::where('row_id', $current_row_id)->first();
        return view('admin.accountSetting.accountSetting', compact('profile'));
    }
}
