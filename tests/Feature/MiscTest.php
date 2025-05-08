<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MiscTest extends TestCase
{
    /**
     * ルート名の重複チェックテスト
     */
    public function test_duplicate_route_name_checking(): void
    {
        // ルート名を重複させてしまった際にエラーとして検出
        Artisan::call('route:cache');
        Artisan::call('route:clear');

        $this->assertTrue(true);
    }
}
