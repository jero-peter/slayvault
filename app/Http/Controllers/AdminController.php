<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SecondaryAdmin;


class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:secondary_admin,admin']);
        
        // $this->middleware(function ($request, $next) {

        //     $this->user = Auth::user();

        //     return $next($request);
        // });
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

    public function addSubscription(Request $request){
        if(auth()->user()){
            $user = auth()->user();
            $appListing = config('app_data.apps');
            $subscribedApplicationListing = $request->all();
            $constructedIdArray = array();
            foreach($subscribedApplicationListing as $subscription){
                array_push($constructedIdArray,$subscription['id']);
            }
            $user->subscription_list = $constructedIdArray;
            $user->save();

            return response()->json(['user' => $user]);
        }
    }

    public function addSecondaryUser(Request $request){
        if(auth()->user()){
            if(\Hash::check($request->get('currentPass'), auth()->user()->password)){
                    $user = new SecondaryAdmin;
                    $user->name = $request->get('name');
                    $user->email = $request->get('email');
                    $user->password = \Hash::make($request->get('password'));
                    $user->ownership_id = auth()->user()->id;
                    $user->company = auth()->user()->company;

                    $user->save();

                    return response()->json(['success' => 'Secondary Administrator Added Successfully', 'updatedUserList' => auth()->user()->admins]);
            }else{
                return response()->json(['error' => 'Wrong Owner Password']);
            }
        }
    }

    public function removeSecondaryUser($id, $encodedRequestCode, Request $request){
        if(auth()->user()){
            // if(\Hash::check($request->get('currentPass'), auth()->user()->password)){
                if(base64_decode($encodedRequestCode) == auth()->user()->company){
                    $selectedSecondaryAdmin = SecondaryAdmin::findOrFail($id);
                    $selectedSecondaryAdmin->delete();
                    return response()->json(['success' => 'User Removed Successfully']);
                }
                
            // }
        }
    }

}
