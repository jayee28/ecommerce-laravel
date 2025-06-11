<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CmsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/admin')->group(function(){
    Route::match(['get','post'],'login',[AdminController::class,'login']);
    Route::group(['middleware' => ['admin']],function(){
        Route::get('dashboard',[AdminController::class,'dashboard']);
        Route::match(['get','post'],'update-password',[AdminController::class,'updatePassword']);
        Route::match(['get','post'],'update-details',[AdminController::class,'updateDetails']);
        Route::post('check-current-password',[AdminController::class,'checkCurrentPassword']);
        Route::get('logout',[AdminController::class,'logout']);

        //Display CMS pages (CRUD - READ)
        Route::get('cms-pages',[CmsController::class,'index']);
        Route::post('update-cms-page-status',[CmsController::class,'update']);
        Route::match(['get','post'],'add-edit-cms-page',[CmsController::class,'edit']);
    });

});