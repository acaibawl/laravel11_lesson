<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AvatarController extends Controller
{
    public function index()
    {
        return view('avatars.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'img' => ['required', 'mimes:jpg,png,gif'],
        ]);

        // public ディスクを使用し avatars フォルダに保存する
        $path = $request->file('img')->store('avatars', 'public');

        // 通常はDBに保存処理等
        return $path;
    }
}
