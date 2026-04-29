<?php

namespace App\Http\Controllers;

use App\Models\SiteAdmin;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(){
        $sited_admin = SiteAdmin::first();
        return view('welcome', compact('sited_admin'));
    }
}
