<?php

use App\Http\Controllers\Admin\AcceptTopup\AcceptTopupController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ManageUser\LinkRegisterController;
use App\Http\Controllers\Admin\ManageUser\ManageUserController;
use App\Http\Controllers\User\TopUp\TopupController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::middleware(['go_to_login_page'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register/{admin_id}', function ($admin_id) {
        return view('auth.register', compact('admin_id'));
    });
});



Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/test/{id}', function ($id) {
    // return dd(Auth::guard('user')->check());
    return dd($id);
});

Route::middleware(['auth:user'])->group(function () {
    Route::get('user/home', [TopupController::class, 'index'])->name('user.home');
    Route::post('user/top_up/store', [TopupController::class, 'store'])->name('user.top_up.store');
    Route::get('user/top_up/history', [TopupController::class, 'history'])->name('user.top_up.history');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/home', [HomeController::class, 'index'])->name('admin.home');
    Route::get('admin/accept/top_up/view', [AcceptTopupController::class, 'index'])->name('admin.accept.top_up.view');
    Route::post('admin/top_up/accept/{id}/{cust_id}/{type}/{note}', [AcceptTopupController::class, 'accept']);
    Route::post('admin/top_up/all_accept/store', [AcceptTopupController::class, 'store_selection'])->name('admin.top_up.all_accept.store');
    Route::get('admin/top_up/history', [AcceptTopupController::class, 'history'])->name('admin.top_up.history');
    //จัดการลูกค้า
    //1.1ลิงค์ลูกค้า
    Route::get('admin/manage_user/link_register/view', [LinkRegisterController::class, 'index'])->name('admin.manage_user.link_register.view');
    //1.2 จัดการลูกค้า
    Route::get('admin/manage_user/view', [ManageUserController::class, 'index'])->name('admin.manage_user.view');
    Route::post('admin/get_api/get_agent', [ManageUserController::class, 'get_agent'])->name('admin.get_api.get_agent');
});
