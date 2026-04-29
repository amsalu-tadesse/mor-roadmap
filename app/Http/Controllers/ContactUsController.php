<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\DataTables\ContactUsDataTable;
use App\Http\Requests\StoreContactMessageRequest;
use App\Http\Requests\StoreContactUsRequest;
use App\Http\Requests\UpdateContactUsRequest;
use App\Models\Contact;
use App\Models\ContactMessage;
use App\Models\Email;
use App\Services\EmailService;
use App\Traits\ModelAuthorizable;
use Exception;

class ContactUsController extends Controller
{


    // use ModelAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(ContactUsDataTable $dataTable)
    {
        return $dataTable->render('admin.contactUs.index');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function save(StoreContactUSRequest $request, EmailService $emailService)
    {
        Contact::create($request->validated());

        //Mailing
        $messageObj = Email::where('code', 'email:on_contact_us')->first();
        // dd($messageObj->status);
        if ($messageObj->status == 1) {
            $body = $messageObj->body;
            $link = Constants::DOMAIN;

            // $message['title'] = $messageObj->subject;
            // $message['body'] = $body;

            $username = 'Citizen';
            if($request->input('name'))
            {
                $username = $request->input('name');
            }
            $body = str_ireplace("{user}", $username, $body);
            $body = str_ireplace("{link}", $link, $body);

            $message['title'] = $messageObj->subject;
            $message['body'] = $body;

            try{
                //$emailService->sendMail($request->input('email'), null, $message);
                dispatch(new \App\Jobs\SendEmailJob([$request->input('email')], [], $message));
            }
            catch(Exception $ex)
            {

            }

        }

        $response['success'] = 'We have received your message. Thanks for contacting us';

        return response()->json($response);
    }





    /**
     * Display the specified resource.
     */
    public function show(Contact $contact_u)
    {
        return response()->json($contact_u);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function storeReply(StoreContactMessageRequest $request, EmailService $emailService)
    {

        if (request()->input('repliedMessage')) {
            $contact_message = ContactMessage::create($request->validated());
            $contact_message->contact_id = request()->input('contactusId');
            $contact_message->message = request()->input('repliedMessage');
            $contact_message->save();

            $contact = Contact::where('id', request()->input('contactusId'))->first();
            $email = $contact->email;
            //Mailing
            $messageObj = Email::where('code', 'email:on_contact_us')->first();
        //    dd($messageObj );
            // dd($messageObj->status);
            if ($messageObj->status == 1) {


                $link = Constants::DOMAIN;

                // $message['title'] = $messageObj->subject;
                // $message['body'] = $body;

                $username = 'Sir/Madam';
                if($contact->name)
                {
                    $username = $contact->name;
                }
                // $body = str_ireplace("{user}", $username, $contact_message->message);
                // $body = str_ireplace("{link}", $link, $body);

                $message['title'] = "Update On User Mangment DataBase System";
                $message['body'] = "Dear ".$username.", <br><br>".$contact_message->message;


                 $emailService->sendMail([$email], [], $message);
                //dispatch(new \App\Jobs\SendEmailJob([$email], [], $message));
            }
        }



        return redirect()->route('admin.contact-us.index')->with('success_create', 'successfuly replied!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact_u)
    {
        if (!$contact_u->exists()) {
            return redirect()->route('admin.contactUs.index')->with('error', 'Unautorized!');
        }
        $contact_u->delete();
        return response()->json(array("success" => true), 200);
    }
}
