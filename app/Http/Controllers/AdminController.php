<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function logoutEverywhere(Request $request){
        if(\Hash::check($request->get('currentPass'), auth()->user()->password)){
            $currentPassword = $request->get('currentPass');
            Auth::logoutOtherDevices($currentPassword);
            return response()->json(['success' => 'Logged Out Everywhere']);
        }else{
            return response()->json(['error' => 'Wrong Current Password']);
        }
  
    }
    
    public function changePassword(Request $request){
        if(\Hash::check($request->get('currentPass'), auth()->user()->password)){
            $user = auth()->user();
            $user->password = \Hash::make($request->get('newPass'));
            $user->save();

            return response()->json(['success' => 'Password Successfully Changed']);
        }else{
            return response()->json(['error' => 'Wrong Current Password']);
        }
        
    }
}
