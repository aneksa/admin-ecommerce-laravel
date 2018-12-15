<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
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
            $cover = $request->file('cover');
            if($cover) {
                $cover = $cover->store('/storage/cover/products');
            }
            DB::table('products')->insert([
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description,
                'stock' => $request->stock,
                'cover' => $cover,
                'created_by' => Auth::guard('admin')->user()->id,
                'created_at' => Carbon::now('Asia/Jakarta')
            ]);
            DB::commit();
            return response([
                'title' => 'Success',
                'icon' => 'success',
                'message' => 'Products has been successfully created'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            if(file_exists($cover)) {
                unlink($cover);
            }
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
        $query->category = DB::table('categories')->where('id', $query->category)->select('id', 'name')->first();
        return response([
            'data' => $query
        ]);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $product = DB::table('products')->where('id', $request->id);
            $tmpProduct= $product->first();
            if($product->count()) {
                $cover = $request->file('cover');
                if($cover) {
                    $cover = $cover->store('/storage/cover/products');
                } else {
                    $cover = $tmpProduct->cover;
                }
                $product->update([
                    'name' => $request->name,
                    'category' => $request->category,
                    'description' => $request->description,
                    'stock' => $request->stock,
                    'cover' => $cover,
                    'updated_at' => Carbon::now('Asia/Jakarta')
                ]);
            } else {
                throw new \Exception('Product not found.');
            }
            DB::commit();
            if($tmpProduct->cover != $cover) {
                if(file_exists($tmpProduct->cover)) {
                    unlink($tmpProduct->cover);
                }
            }
            return response([
                'title' => 'Success',
                'icon' => 'success',
                'message' => 'Product has been successfully updated'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            if($tmpProduct->cover != $cover) {
                if(file_exists($cover)) {
                    unlink($cover);
                }
            }
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
            $product = DB::table('products')->where('id', $request->id);
            if($product->count()) {
                $product->update([
                    'deleted_at' => Carbon::now('Asia/Jakarta')
                ]);
            } else {
                throw new \Exception('Product not found.');
            }
            DB::commit();
            return response([
                'title' => 'Success',
                'icon' => 'success',
                'message' => 'Product has been successfully deleted'
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

    public function categoryList(Request $request)
    {
        $queries = DB::table('categories')
            ->whereNull('deleted_at')
            ->where('name', 'like', '%'.$request->q.'%')
            ->select('id', 'name as text')
            ->get();
        return response([
            'items' => $queries
        ]);
    }

    public function listDataTable(Request $request)
    {
        $queries = DB::table('products')
            ->whereNull('deleted_at')
            ->where('name', 'like', '%'.$request->search['value'].'%')
            ->orderBy('id', 'desc')
            ->select('id', 'name', 'stock');
        $total = $queries->count();
        return response([
            'draw'              => $request->draw,
            'recordsTotal'      => $total,
            'recordsFiltered'   => $total,
            'data'              => $queries->limit($request->length)->offset($request->start)->get()
        ]);
    }
}
