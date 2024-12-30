<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\WebController;


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


Route::get('/',[WebController::class,'index'])->name('index');


Route::group(['prefix'=>'admin','middleware'=>['auth']], function () {
    Route::get('/dashboard', function () {
        $userCount = \App\Models\User::count();
        $userCount = $userCount-2;
        return view('AdminDashboard',compact('userCount'));
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::post('/assign-role-permission',[PermissionController::class,'assignRolePermission'])->name('assignRolePermission');
    Route::resource('users', UserController::class);
});
Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);
Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');



//Route::get('/test', [DashboardController::class, 'dashboard'])->name('userDashboard');
Route::group(['prefix'=>'dashboard','middleware'=>['auth']], function () {

});

Route::get('/download',[DashboardController::class,'download'])->name('download');




Route::group(['prefix' => 'dev'], function () {
    Route::get('/clear-cache',function(){
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');
        //    \Artisan::call('view:cache');
        \Artisan::call('view:clear');

        // Alert::success('Cache has been cleared !')->persistent('Close')->autoclose(6000);

        return back();
    })->name('purgeLocalCache');
});
require __DIR__.'/auth.php';
