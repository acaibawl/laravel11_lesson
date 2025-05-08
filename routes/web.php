<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostManageController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IpLimit;
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


// ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ testの練習用ルート ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
Route::get('/users', [UserController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/login', function () {
    return 'ログイン画面';
})->name('auth');
Route::middleware('auth')->group(function () {
    // 認証が必要なページ
    Route::get('/members', [MemberController::class, 'index']);

    Route::get('members/posts', [PostManageController::class, 'index'])->name('posts.index');
    Route::post('members/posts', [PostManageController::class, 'store'])->name('posts.store');
    Route::get('members/posts/{post}/edit', [PostManageController::class, 'edit'])->name('posts.edit');
    Route::put('members/posts/{post}', [PostManageController::class, 'update'])->name('posts.update');
    Route::delete('members/posts/{post}', [PostManageController::class, 'destroy'])
        ->name('posts.destroy')
        ->middleware(IpLimit::class);
});

Route::get('/avatar', [AvatarController::class, 'index']);
Route::post('/avatar', [AvatarController::class, 'store']);
