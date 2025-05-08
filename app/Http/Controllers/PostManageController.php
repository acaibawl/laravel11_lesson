<?php

namespace App\Http\Controllers;

use App\Mail\BlogPosted;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostManageController extends Controller
{
    public function index(): View
    {
        $validator = \Validator::make(request()->all(), [
            'keyword' => ['nullable', 'string', 'max:100'],
        ]);

        abort_if($validator->fails(), 422);
        $data = $validator->validated();
        $query = Post::query()
            ->where('user_id', auth()->user()->id);

        // キーワード検索
        if ($val = data_get($data, 'keyword')) {
            $query->where(function (Builder $query) use ($val) {
                $query->where('body', 'LIKE', "%{$val}%");
            });
        }


        $posts = $query->get();
        return view('members.posts.index', compact('posts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:255'],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        $user = auth()->user();
        $post = $user->posts()->create($data);

        Mail::send(new BlogPosted($user, $post));

        return to_route('posts.edit', ['post' => $post])
            ->with('status', 'ブログを投稿しました');
    }

    public function edit(Post $post): View
    {
        if (auth()->user()->isNot($post->user)) {
            abort(403);
        }

        $data = old() ?: $post;

        return view('members.posts.edit', compact('post', 'data'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        if (auth()->user()->isNot($post->user)) {
            abort(403);
        }

        $data = $request->validate([
            'body' => ['required', 'string', 'max:255'],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        $post->update($data);


        return to_route('posts.edit', ['post' => $post])
            ->with('status', 'ブログを更新しました');
    }

    public function destroy(Post $post)
    {
        if (auth()->user()->isNot($post->user)) {
            abort(403);
        }

        $post->delete();
        return to_route('posts.index')
            ->with('status', 'ブログを削除しました');
    }
}
