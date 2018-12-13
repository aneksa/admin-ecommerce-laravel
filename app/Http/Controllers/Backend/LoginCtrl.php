<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use Auth;
use Carbon\Carbon;
use DB;

class LoginCtrl extends Controller
{
    //

    public function index()
    {
        return view('backend.login');
    }

    public function login(Request $request)
    {
        // throw new \Exception('test');
        $user = Admin::where('email', '=', $request->email)->first();
        if($user) {
            if($user->deleted_at == null) {
                if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password]) ) {
                    $user = Auth::guard('admin')->user();
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

    public function logout()
    {
        Auth::guard('admin')->logout();
        return response([
            'success' => true,
            'title' => 'Success',
            'icon' => 'success',
            'message' => 'Logout'
        ]);
    }
}
