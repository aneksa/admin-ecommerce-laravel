<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix'=>'backend', 'namespace'=>'Backend'],function() {
    Route::get('/', function() {
        if(Auth::guard('admin')->check()) {
            return redirect('/backend/dashboard');
        } else {
            return redirect('/backend/login');
        }
    });

    Route::get('/dashboard', 'DashboardCtrl@index');

    Route::get('/login', 'LoginCtrl@index');
    Route::post('/login', 'LoginCtrl@login');

    Route::post('/logout', 'LoginCtrl@logout');

    Route::group(['middleware'=>'authAdmin'], function() {
        //Profile
        Route::get('/profile', 'AdminCtrl@profile');

        //categories
        Route::get('/categories', 'CategoryCtrl@index');
        Route::post('/categories/create', 'CategoryCtrl@store');
        Route::get('/categories/edit', 'CategoryCtrl@edit');
        Route::put('/categories/update', 'CategoryCtrl@update');
        Route::delete('/categories/soft-delete', 'CategoryCtrl@softDelete');
        Route::get('/categories/list/datatable', 'CategoryCtrl@listDataTable');

        //product
        Route::get('/products', 'ProductCtrl@index');
        Route::post('/products/create', 'ProductCtrl@store');
        Route::get('/products/edit', 'ProductCtrl@edit');
        Route::put('/products/update', 'ProductCtrl@update');
        Route::delete('/products/soft-delete', 'ProductCtrl@softDelete');
        Route::get('/products/category/list', 'ProductCtrl@categoryList');
        Route::get('/products/list/datatable', 'ProductCtrl@listDataTable');
    });
    
});