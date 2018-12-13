<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class DashboardCtrl extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('authAdmin');
    }

    public function index()
    {
        $user = Auth::guard('admin')->user();
        return view('backend.dashboard', compact(['user']));
    }
}
