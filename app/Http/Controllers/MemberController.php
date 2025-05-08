<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return 'ようこそ' . $user->name . 'さん！';
    }
}
