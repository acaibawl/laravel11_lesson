<?php

use App\Http\Middleware\LogUserNameMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // webの未認証リダイレクト処理
        $middleware->redirectGuestsTo(function (Request $request) {
            // もし `admin.*` という名前付きルート配下なら管理画面用のログインへ
            if (request()->routeIs('admin.*')) {
                // jsonを要求するリクエストの場合はnullを返す
                return $request->expectsJson() ? null : route('admin.login');
            }
            // それ以外は通常ユーザー用のログインへ
            return $request->expectsJson() ? null : route('auth');
        });

        // apiという既存のmiddlewareグループにmiddlewareを追加
        $middleware->api(append: [
            LogUserNameMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
