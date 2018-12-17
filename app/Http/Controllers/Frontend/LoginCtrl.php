<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class LoginCtrl extends Controller
{
    //
    public function login(Request $request)
    {
        // throw new \Exception('test');
        $user = User::where('email', '=', $request->email)->first();
        if($user) {
            if($user->deleted_at == null) {
                if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password]) ) {
                    $user = Auth::guard('user')->user();
                    // throw new \Exception($user);
                    session()->flash('message', 'Welcome back '.$user->name);
                    session()->flash('title', 'Success');
                    session()->flash('icon', 'success');
                    return response([
                        'success' => true,
                        'title' => 'Success',
                        'icon' => 'success',
                        'message' => 'Welcome '.$user->name
                    ]);
                } else {
                    return response([
                        'success' => false,
                        'title' => 'Error',
                        'icon' => 'error',
                        'message' => 'Email and password not match.'
                    ]);
                }
            } else {
                return response([
                    'success' => false,
                    'title' => 'Error',
                    'icon' => 'error',
                    'message' => 'Your account is not active.'
                ]);
            }
        } else {
            return response([
                'success' => false,
                'title' => 'Error',
                'icon' => 'error',
                'message' => 'Email is not found.'
            ]);
        }

    }
}
