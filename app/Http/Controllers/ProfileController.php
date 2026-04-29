<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
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
