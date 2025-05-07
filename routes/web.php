<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 管理者ログイン画面
Route::get('/admin-login', [AdminLoginController::class, 'create'])->name('admin.login');

// 管理者ログイン処理
Route::post('/admin-login', [AdminLoginController::class, 'store'])->name('admin.login.store');

// 管理者ログアウト
Route::delete('/admin-login', [AdminLoginController::class, 'destroy'])->name('admin.login.destroy');

// 管理者ログイン後のみアクセス可
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin', function () {
        return view('admin.top');
    })->name('admin.top');
});

Route::get('/users', [UserController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);
