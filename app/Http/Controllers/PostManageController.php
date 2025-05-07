<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostManageController extends Controller
{
    public function index(): View
    {
        $posts = auth()->user()->posts;
        return view('members.posts.index', compact('posts'));
    }
}
