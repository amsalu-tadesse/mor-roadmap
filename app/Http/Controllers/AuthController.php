<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Http\Controllers\Utility;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Exception;
use App\Mail\SendMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\CustomException;
use App\Models\Email;
use App\Models\LoginAttempt;
use App\Models\Setting;
use App\Services\EmailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use App\Http\Requests\?

class AuthController extends Controller
{


    /**
     * Login The User
     * @param Request $request
     * @return User
     */

    public function index()
    {
        // $signupAllowed = Setting::find('code'=='allow_user_signup');
        $signupAllowed = Setting::where('code', 'allow_user_signup')->where('value1', 1)->exists();


        // dd($signupAllowed);
        return view('admin.auth.login', (['signupAllowed' => $signupAllowed]));
    }

    public function loginUser(Request $request)
    {
        //  dd("This is Dampping");

        $twofa_code = request()->input('twofa_code');
        $setting = Setting::where('code', 'twofa_code')->first();
       $setting = $setting->value1;



        if ($twofa_code) {
            $user2 = User::where('twofa_code', $twofa_code)->first();

            if ($user2) {
                $user2->email_verified_at = new \DateTime();
                $user2->save();
                if ($user2->password_changed) {
                    return redirect()->route('admin.dashboard');
                } else {
                    return redirect()->route('admin.profile');
                }
            } else {
                return redirect()->route('admin.2fa')->with('message', 'Please enter the correct 2FA code.');
            }
        } else {
            try {
                $validateUser = Validator::make(
                    $request->all(),
                    [
                        'email' => 'required|email',
                        'password' => 'required'
                    ]
                );
                if ($validateUser->fails()) {
                    return redirect("/login")->withSuccess('Opps! You do not have access');
                }
                if (!Auth::attempt($request->only(['email', 'password']))) {
                    return redirect("/login")->withSuccess('Opps! You do not have access');
                }


                //check if email is verified
                $user = Auth::user();

                if ($user) {

                    if ($setting == '1' && $user->is_superadmin != '1') {
                        $twofa_code = Str::password(6);
                        // Get fresh instance from database
                        $user = User::find($user->id);
                        $user->twofa_code = $twofa_code;
                        $user->save();

                        $email = $user->email;

                        $message['title'] = "Two Factor Authentication code";
                        $message['body'] = $twofa_code;

                        try {
                            dispatch(new \App\Jobs\SendEmailJob([$email], [], $message));
                        } catch (Exception $ex) {
                            return redirect()->route('admin.2fa');
                        }

                        return redirect()->route('admin.2fa');
                    } else {

                        $user = User::find($user->id);
                        $user->email_verified_at = new \DateTime();
                        $user->save();
                        if ($user->password_changed) {
                            LoginAttempt::create([
                                'user_id' => Auth::id(),
                                'login_time' => now(),
                                'ip_address' => request()->ip(),
                                'proxy' => request()->header('X-Forwarded-For'),
                            ]);
                            return redirect()->route('admin.dashboard');
                        } else {
                            LoginAttempt::create([
                                'user_id' => Auth::id(),
                                'login_time' => now(),
                                'ip_address' => request()->ip(),
                                'proxy' => request()->header('X-Forwarded-For') ?? request()->header('X-Real-IP') ?? request()->ip(),

                            ]);
                            return redirect()->route('admin.profile');
                        }
                    }
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        }
    }

    public function twofa()
    {

        return view('admin.auth.2fa');
    }

    public function create()
    {

        //check setting..

        $signupAllowed = Setting::where('code', 'allow_user_signup')->where('value1', 1)->exists();
        $terms = Setting::where('code', 'privacy_policy')->first();

        if ($signupAllowed) {
            return view('admin.auth.register', ([
                'terms' => $terms,
            ]));
        } else {

            // return view('admin.auth.login');
            return Redirect::to('/login');
        }
    }
    public function signup(StoreUserRequest $request, EmailService $emailService)
    {

        $terms = $request->input('terms');

        if ($terms) {
            $user = User::create($request->validated());
            // $password =  Str::password(10);
            $password = Utility::getRandomStringRandomInt(8);
            $user->password = $password;
            $user->save();

            //Mailing
            $messageObj = Email::where('code', 'email:on_user_signup')->first();
            // dd($messageObj->status);
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
                    dd($ex);
                    return redirect()->route('login')->with('message', 'Registration was successfull. Now you can login.');
                }
            }
        }

        return redirect()->route('login')->with('message', 'Registration was successfull. We have sent you a default password to access the system.');
    }

    public function changePasswordSave(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            // 'new_password' => 'required|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            'new_password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $auth = Auth::user();
        if (Hash::check($request->current_password, Auth::user()->password)) {
            $user =  User::find($auth->id);
            $user->password =  Hash::make($request->new_password);
            $user->password_changed = 1;
            $user->save();
            return redirect()->back()->with('success', 'Password changed successfully.');
        } else {
            return redirect()->back()->withErrors(['current_password' => 'Incorrect current password.']);
        }
    }

    public function logout(Request $request)

    {

        $login_user_id = Auth::id();
        $login_user = LoginAttempt::where('user_id', $login_user_id)->latest()->first();
        if($login_user){
            $login_user->logout_time = Carbon::now();
            $login_user->duration = $this->calculateDuration($login_user->login_time, $login_user->logout_time);
            $login_user->save();
        }
        Auth::logout();

        // Forget the cached permissions for roles and permissions
        $role = new Role();

        // Create an instance of the Permission model
        $permission = new Permission();

        // Forget the cached permissions for roles and permissions
        $role->forgetCachedPermissions();
        $permission->forgetCachedPermissions();


        return redirect()->route('login');
    }

    private function calculateDuration($loginTime, $logoutTime)
    {
        $loginTime = Carbon::parse($loginTime);
        $logoutTime = Carbon::parse($logoutTime);
        $durationInSeconds = $logoutTime->diffInSeconds($loginTime);
        return gmdate("H:i:s", $durationInSeconds);
    }
}
