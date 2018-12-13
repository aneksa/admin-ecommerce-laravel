<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;
class AdminCtrl extends Controller
{
    //
    public function profile()
    {
        $user = Auth::guard('admin')->user();
        return view('backend.profile', compact(['user']));
    }
}
