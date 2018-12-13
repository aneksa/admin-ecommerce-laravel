<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class ProductCtrl extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->user();
        return view('backend.products', compact(['user']));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('products')->insert([
                'name' => $request->name,
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
        $query = DB::table('products')->where('id', $request->id)->first();
        return response([
            'data' => $query
        ]);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $category = DB::table('products')->where('id', $request->id);
            if($category->count()) {
                $category->update([
                    'name' => $request->name,
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
            $category = DB::table('products')->where('id', $request->id);
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
        $queries = DB::table('products')
            ->whereNull('deleted_at')
            ->where('name', 'like', '%'.$request->search['value'].'%')
            ->orderBy('id', 'desc')
            ->select('id', 'name');
        $total = $queries->count();
        return response([
            'draw'              => $request->draw,
            'recordsTotal'      => $total,
            'recordsFiltered'   => $total,
            'data'              => $queries->limit($request->length)->offset($request->start)->get()
        ]);
    }
}
