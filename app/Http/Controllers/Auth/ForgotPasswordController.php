<?php

namespace App\Http\Controllers\Auth;

use App\Constants\Constants;
use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\PasswordResetToken;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ForgotPasswordController extends Controller
{
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function showForgetPasswordForm()
      {
         return view('admin.auth.forgetPassword');
      }

      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitForgetPasswordForm(Request $request, EmailService $emailService)
      {
        if (User::where('email', '=', $request->input('email'))->count() > 0) {
            DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();
         }
          $request->validate([
              'email' => 'required|email|exists:users',
          ]);


          $token = Str::random(64);

          DB::table('password_reset_tokens')->insert([
              'email' => $request->email,
              'token' => $token,
              'created_at' => Carbon::now()
            ]);



            $messageObj = Email::where('code', 'email:on_reset_password')->first();
            if($messageObj->status == 1){
            $body = $messageObj->body;
            // $link = Constants::DOMAIN . '/forget-password';

            $user = User::where('email', $request->input('email'))->first();

            $link = route('reset.password.get', ['token' =>  $token]);


            $body = str_ireplace("{user}", $user?->first_name, $body);
            $body = str_ireplace("{link}", $link, $body);

            $message['title'] = $messageObj->subject;
            $message['body'] = $body;

             //$emailService->sendMail($request->email, null, $message);
            dispatch(new \App\Jobs\SendEmailJob([$request->email], [], $message));

            }


         /* Mail::send('email.forgetPassword', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject('Reset Password');
          });*/

          return back()->with('message', 'We have e-mailed your password reset link!');
      }
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function showResetPasswordForm($token) {
         return view('admin.auth.forgetPasswordLink', ['token' => $token]);
      }

      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitResetPasswordForm(Request $request)
      {
        $validator = Validator::make($request->all(), [
          'password' => [
              'required',
              'confirmed',
              'min:8',
              'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!<>%*?&])[A-Za-z\d@$!%*?&]+$/',
          ],
          'password_confirmation' => 'required',
      ], [
          'password.required' => 'The password field is required.',
          'password.confirmed' => 'The password confirmation does not match.',
          'password.min' => 'The password must be at least 8 characters long.',
          'password.regex' => 'The password must contain at least one lowercase letter, one uppercase letter, one digit, and one special character.',
          'password_confirmation.required' => 'The password confirmation field is required.',
      ]);

      if ($validator->fails()) {
          // Handle validation errors here, such as returning the errors to the view.
          return back()->withErrors($validator)->withInput();
      }
          $updatePassword = DB::table('password_reset_tokens')
                              ->where([
                                // 'email' => $request->email,
                                'token' => $request->token
                              ])
                              ->first();

                              $email = $updatePassword->email;


          if(!$updatePassword){
              return back()->withInput()->with('error', 'Invalid token!');
          }

          $user = User::where('email', $email)
                      ->update(['password' => Hash::make($request->password)]);

          DB::table('password_reset_tokens')->where(['email'=> $email])->delete();

          return redirect('/login')->with('message', 'Your password has been changed!');
      }
}
