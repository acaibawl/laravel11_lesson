<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostManageController extends Controller
{
    public function index(): View
    {
        $posts = auth()->user()->posts;
        return view('members.posts.index', compact('posts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:255'],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        $post = auth()->user()->posts()->create($data);
        return to_route('posts.edit', ['post' => $post])
            ->with('status', 'ブログを投稿しました');
    }

    public function edit(Post $post)
    {
        if (auth()->user()->isNot($post->user)) {
            abort(403);
        }

        $data = old() ?: $post;

        return view('members.posts.edit', compact('post', 'data'));
    }
}
