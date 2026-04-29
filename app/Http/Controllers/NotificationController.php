<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{

    public function index()
    {
        // Get all notifications
        $notifications = Notification::latest()->get();


        // Update the is_seen field for each unread notification
        foreach ($notifications as $notification) {
            if (!$notification->is_seen) {
                $notification->update(['is_seen' => 1]);
            }
        }



        return view("admin.notification.index", [
            "notificationsList" => $notifications,
        ]);
    }


    public function destroy(Request $request,$id){
         $notification=Notification::find($id);

         if (!$notification->exists()) {
            return redirect()->route('admin.notifications.index')->with('error', 'Unautorized!');
        }
        $notification->delete();
        return response()->json(array("success" => true), 200);
    }

    public function update(Request $request){
        $notificationId = $request->input('notification_id');

    // Update the is_seen column in the database
    $notification = Notification::find($notificationId);
    if ($notification && !$notification->is_seen) {
        $notification->update(['is_seen' => 1]);
        return response()->json(['success' => true]);

        // Return a JSON response to indicate success
    }


    // Return a JSON response to indicate failure
    return response()->json(['success' => false]);
    }


}
