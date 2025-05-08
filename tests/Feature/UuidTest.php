<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class UuidTest extends TestCase
{
    /**
     * enterにアクセスするとUUIDで生成されたページに飛ぶ
     */
    public function test_redirect_uuid_url(): void
    {
        $uuid = (string) Str::uuid();
        Str::createUuidsUsingSequence([
            $uuid,
        ]);
        $this->get('/enter')
            ->assertRedirect("/result/{$uuid}");
    }
}
