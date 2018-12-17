<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class HomeCtrl extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        $categories = DB::table('categories')->whereNull('deleted_at')->get();
        $popularProducts = DB::table('products')->whereNull('deleted_at')->get();
        return view('frontend.home', compact(['user', 'categories', 'popularProducts']));
    }
}
