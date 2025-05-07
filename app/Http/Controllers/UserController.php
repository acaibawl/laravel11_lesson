<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }
}
