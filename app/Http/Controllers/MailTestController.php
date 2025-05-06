<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MailTestController extends Controller
{
    public function send(): JsonResponse
    {
        \Mail::raw('test from laravel & ses本文です。', function($message) {
            $message->to('atesaki@example.com')->subject('testメールタイトルです。');
        });
        return response()->json(['message' => 'success']);
    }
}
