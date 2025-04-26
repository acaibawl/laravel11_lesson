<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PostRelationTestController extends Controller
{
    public function index()
    {
//        $user = User::where('id', 7)->first();
//        $posts = $user->hoge;
//        foreach ($posts as $post) {
//            dump($post->body);
//        }

        // プロパティアクセスでリレーション先のデータを取得
        \DB::enableQueryLog();
        /** @var Collection<User> $users */
        $users = User::with([
            'posts',
        ])->get();

        foreach ($users as $user) {
            foreach ($user->posts as $post) {
                dump($post->body);
            }
        }
        \Log::info(\DB::getQueryLog()); // 発行されたクエリを確認

        // リレーションメソッドでリレーション先のテーブルに対して操作
        $user = User::first();
        $newPosts = $user->posts()->createMany([
            ['body' => 'hello!'],
            ['body' => 'good!'],
        ]);
        dump($newPosts);
    }
}
