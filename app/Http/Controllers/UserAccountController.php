<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserAccountController extends Controller
{
    public function get(){
        return response()->json(User::get());
    }
}
