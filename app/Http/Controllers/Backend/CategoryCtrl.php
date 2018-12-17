<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;

class CategoryCtrl extends Controller
{
    //
    public function index()
    {
        $user = Auth::guard('admin')->user();
        return view('backend.categories', compact(['user']));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('categories')->insert([
                'name' => $request->name,
                'code' => $request->code,
                'created_at' => Carbon::now('Asia/Jakarta')
            ]);
            DB::commit();
            return response([
                'title' => 'Success',
                'icon' => 'success',
                'message' => 'Category has been successfully created'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'title' => 'Error',
                'icon' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit(Request $request)
    {
        $query = DB::table('categories')->where('id', $request->id)->first();
        return response([
            'data' => $query
        ]);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $category = DB::table('categories')->where('id', $request->id);
            if($category->count()) {
                $category->update([
                    'name' => $request->name,
                    'code' => $request->code,
                    'updated_at' => Carbon::now('Asia/Jakarta')
                ]);
            } else {
                throw new \Exception('Category not found.');
            }
            DB::commit();
            return response([
                'title' => 'Success',
                'icon' => 'success',
                'message' => 'Category has been successfully updated'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'title' => 'Error',
                'icon' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function softDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $category = DB::table('categories')->where('id', $request->id);
            if($category->count()) {
                $category->update([
                    'deleted_at' => Carbon::now('Asia/Jakarta')
                ]);
            } else {
                throw new \Exception('Category not found.');
            }
            DB::commit();
            return response([
                'title' => 'Success',
                'icon' => 'success',
                'message' => 'Category has been successfully deleted'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'title' => 'Error',
                'icon' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function listDataTable(Request $request)
    {
        $queries = DB::table('categories')
            ->whereNull('deleted_at')
            ->where('name', 'like', '%'.$request->search['value'].'%')
            ->orderBy('id', 'desc')
            ->select('id', 'name', 'code');
        $total = $queries->count();
        return response([
            'draw'              => $request->draw,
            'recordsTotal'      => $total,
            'recordsFiltered'   => $total,
            'data'              => $queries->limit($request->length)->offset($request->start)->get()
        ]);
    }
}
