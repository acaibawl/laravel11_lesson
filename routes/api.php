<?php

use App\Http\Controllers\ApiUserAuthController;
use App\Http\Controllers\MailTestController;
use App\Http\Controllers\PostRelationTestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hello', function () {
    return response()->json(['message' => 'Hello, World!']);
});

Route::post('/tasks', [\App\Http\Controllers\TaskController::class, 'store']);
Route::get('/tasks/{id}', [\App\Http\Controllers\TaskController::class, 'show'])->where('id', '[0-9]+');

// prefixにauthを指定すると、中で指定したuriはすべて/auth/xxxになる
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function () {
    Route::post('login', [ApiUserAuthController::class, 'login']);
    // middlewareのauthは、'auth:{guards名}'で指定できる
    Route::post('logout', [ApiUserAuthController::class, 'logout'])->middleware('auth:api_user');
    Route::post('refresh', [ApiUserAuthController::class, 'refresh'])->middleware('auth:api_user');;
    Route::post('me', [ApiUserAuthController::class, 'me'])->middleware('auth:api_user');;
});
Route::post('/register', [ApiUserAuthController::class, 'register']);

Route::get('/postRelationTest', [PostRelationTestController::class, 'index']);
Route::get('/mailTest', [MailTestController::class, 'send']);
