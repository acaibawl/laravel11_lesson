<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Middleware\IpLimit;
use App\Mail\BlogPosted;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class PostManageControllerTest extends TestCase
{
    public function test_only_my_posts_showed()
    {
        $me = $this->login();

        Post::factory()->for($me)->create(['body' => '私のブログ本文']);
        Post::factory()->create(['body' => '他人様のブログ本文']);

        $response = $this->get('/members/posts');

        $response
            ->assertOk()
            ->assertSee('私のブログ本文')
            ->assertDontSee('他人様のブログ本文');
    }

    public function test_can_create_my_new_post()
    {
        // 実際にはメールそうし処理をしないように設定
        Mail::fake();

        $me = User::factory()->create([
            'name' => '織田信長',
            'email' => 'oda@example.net',
        ]);

        $this->login($me);

        $validData = [
            'body' => '私のブログ本文',
            'status' => '1',
        ];

        $response = $this->post(route('posts.store'), $validData);
        $post = Post::first();
        $response->assertRedirectToRoute('posts.edit', ['post' => $post])
            ->assertSessionHas('status', 'ブログを投稿しました');
        // リダイレクト後

        // コントローラでメール送信処理がされたことを確認
        Mail::assertSent(BlogPosted::class);

        $post = Post::first();

        $mailable = new BlogPosted($me, $post);
        $mailable->assertTo($me->email);
        $mailable->assertSeeInText('本文： ' . $post->body);

        $this->assertDatabaseHas('posts',
            [...$validData, 'user_id' => $me->id]
        );
    }

    /*
     * 自分のブログ投稿の編集画面（URL）は、開く事ができる
     */
    public function test_can_open_own_post_edit_page()
    {
        $post = Post::factory()->create();
        $this->login($post->user);
        $this->get(route('posts.edit', ['post' => $post]))
            ->assertOk();
    }

    /*
     * 他人様のブログ投稿の編集画面（URL）は、開く事ができない
     */
    public function test_cant_open_others_post_edit_page()
    {
        $post = Post::factory()->create();
        $this->login();

        $this->get(route('posts.edit', ['post' => $post]))
            ->assertForbidden();
    }

    /*
     * 自分のブログ投稿の更新ができる
     */
    #[TestWith([0])]
    #[TestWith([1])]
    public function test_can_update_own_post(int $status)
    {
        $validData = $this->validData([
            'status' => $status
        ]);
        $post = Post::factory()->create();
        $this->login($post->user);

        $this->put(route('posts.update', ['post' => $post]), $validData)
            ->assertRedirectToRoute('posts.edit', ['post' => $post])
            ->assertSessionHas('status', 'ブログを更新しました');
        $this->assertDatabaseHas('posts', $validData);
        $this->assertDatabaseCount('posts', 1);
    }

    /*
     * 他人様のブログ投稿は更新できない
     */
    public function test_cant_update_others_post()
    {
        $validData = [
            'body' => '新本文',
            'status' => '1',
        ];
        $post = Post::factory()->create(['body' => '元の本文']);
        $this->login();

        $this->put(route('posts.update', ['post' => $post]), $validData)
            ->assertForbidden();
        $post->refresh();
        $this->assertSame('元の本文', $post->body);
    }

    /*
     * 自分のブログ投稿の削除ができる
     */
    public function test_can_delete_own_post()
    {
        // 特定のミドルウェアを外す
        // $this->withoutMiddleware(IpLimit::class);

        $post = Post::factory()->create();
        $this->login($post->user);

        $this->delete(route('posts.destroy', ['post' => $post]))
            ->assertRedirectToRoute('posts.index');

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    /*
     * 他人様のブログを削除はできない
     */
    public function test_cant_delete_others_post()
    {
        $post = Post::factory()->create();
        $this->login();

        $this->delete(route('posts.destroy', ['post' => $post]))
            ->assertForbidden();

        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    private function validData(array $overrides = []): array
    {
        return [
            'body' => '新本文',
            'status' => 1,
            ...$overrides,
        ];
    }

    /**
     * 投稿一覧、本文でヒット
     */
    public function test_list_of_posts_hits_in_body()
    {
        $me = $this->login();
        Post::factory()->for($me)->createMany([
            ['body' => '信長の本文'],
            ['body' => '家康の本文'],
        ]);

        $response = $this->get('members/posts?keyword=信長の本');

        $response->assertOk()
            ->assertSee('信長の本文')
            ->assertDontSee('家康の本文');
    }

    public function test_create_post_validation()
    {
        $url = route('posts.store');
        $this->login();

        // 何も入力しないで送信した際は、リダイレクトされるのを確認
        $this->post($url, [])->assertRedirect('/');

        // bodyの入力チェック
        $this->post($url, ['body' => ''])->assertInvalid(['body' => 'The body field is required.']);
        $this->post($url, ['body' => ['aa' => 'bb']])->assertInvalid('body');
        $this->post($url, ['body' => str_repeat('a', 256)])->assertInvalid('body');

        // statusの入力チェック
        $this->post($url, ['status' => ''])->assertInvalid('status');
        $this->post($url, ['status' => 'aa'])->assertInvalid('status');
        $this->post($url, ['status' => 2])->assertInvalid('status');

        // postのresponseでdumpSession()を呼ぶとエラーの内容が見られる
    }
}
