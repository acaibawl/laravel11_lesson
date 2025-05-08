<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DownloadControllerTest extends TestCase
{
    /**
     * ダウンロードのテスト
     */
    public function test_csv_download()
    {
        User::factory()->createMany([
            ['name' => 'taro', 'email' => 'taro@example.net'],
            ['name' => 'jiro', 'email' => 'jiro@example.org'],
        ]);

        // 期待するCSVの中身
        $expected = <<<EOM
名前,メールアドレス
taro,taro@example.net
jiro,jiro@example.org

EOM;  // 改行を上に入れる為1行入れる

        // $expected = mb_convert_encoding($expected, 'CP932'); 必要であれば、SJISに変換等

        $response = $this->get('/download')
            ->assertOk()
            ->assertDownload('users.csv')  // ファイル名のチェック
            ->assertStreamedContent($expected);     // ファイルの中身が完全一致するかチェック

        // 項目が多い時は、CSV の全中身までチェックするのは大変な事もあります。
        // そういう時は、大事な箇所の部分チェックでも良いかもしれません
        $downloadContent = $response->streamedContent();
        $this->assertStringContainsString('taro,taro@example.net', $downloadContent);
    }
}
