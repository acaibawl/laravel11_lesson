<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class AvatarControllerTest extends TestCase
{
    /**
     * 画像が保存されるか
     */
    public function test_will_the_image_be_saved()
    {
        // fakeで指定したdiskに、実際には画像が保存されなくなる
        Storage::fake('public');

        // フォームからアップロードした程のファイルを作成
        $file = UploadedFile::fake()->image('yotaro.jpg');

        $response = $this->post('/avatar', [
            'img' => $file
        ]);

        $response->assertOk();
        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }
}
