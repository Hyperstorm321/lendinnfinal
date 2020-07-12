<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserAccountController extends Controller
{

    public function index(){
        return view('auth.loggedIn.userSetting');
    }

    public function get(Request $request, $user_id){
        $userinfo = User::where('user_id',$user_id)->get();

        return response()->json($userinfo);
    }

    public function put(Request $request, $user_id){
        $userinfo = User::find($user_id);
        $userinfo->first_name = $request->input('first_name');
        $userinfo->last_name = $request->input('last_name');
        $userinfo->middle_name = $request->input('middle_name');
        $userinfo->extension_name = $request->input('extension_name');
        $userinfo->detailed_address = $request->input('detailed_address');
        $userinfo->barangay_id = $request->input('barangay_id');
        $userinfo->postal_code_id = $request->input('postal_code_id');
        $userinfo->city_id = $request->input('city_id');
        $userinfo->region_id = $request->input('region_id');
        $userinfo->province_id = $request->input('province_id');

        $userinfo->save();

        // return response()->json($userinfo);
    }


}
