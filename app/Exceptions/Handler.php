<?php

namespace App\Exceptions;

use App\Constants\Constants;
use App\Models\CustomException;
use App\Services\EmailService;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,

    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $exception)
    {
        $message = '';
        $errorCode = 0;
        // dd($exception);
        if ($exception instanceof \Illuminate\Http\Exceptions\PostTooLargeException) {

            $message = 'file size is too large.';
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {

            $message = 'You need to login first.';
        }
        if ($exception instanceof \Illuminate\Validation\ValidationException) {

            $message = $exception->getMessage();
            // Add anywhere in this method the following code
            // It does what the FormValidator does.
            // if (request()->ajax())
                // return response()->json(['errors' => $exception->validator->getMessageBag()], 422);

            // return redirect()->back()->withErrors($exception->validator->getMessageBag()->toArray());
        }
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {

            $message = 'Record not found with the given ID';
        }

        if ($message == '') {
            $msg = $exception?->getMessage();
            if ($msg && strlen($msg) < 100) {
                $message = $msg;
            } else {

                $message = 'Something went wrong.';


                // dd($exception);
                // $msg_email['title'] = Constants::EXCEPTION_EMAIL_TITLE;
                // $msg_email['body'] = 'User: '. Auth()?->user().'<br> Message: '.$exception;
                // (new EmailService)->sendMail(Constants::EXCEPTION_EMAIL_ADDRESSS, [], $msg_email);



            }
        }

        if ($message == '') {
            $message = substr($exception, 0, 60);
        }
        //insert it into database.
        $exception1 = explode('Stack trace:', $exception)[0];
        $customException = new  CustomException();
        $customException->title = $message;
        $customException->description = $exception1;
        $customException->code = $errorCode;
        $customException->save();

        // return redirect()->route('admin.customExceptions.index');

        return parent::render($request, $exception);
    }
}
