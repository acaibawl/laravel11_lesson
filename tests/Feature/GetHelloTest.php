<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetHelloTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        dump('setUp');
    }

    protected function tearDown(): void
    {
        dump('tearDown');
        parent::tearDown();
    }
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        dump('test_example');
        $response = $this->get('/api/hello');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Hello, World!',
        ]);
    }

    public function test_example2()
    {
        $response = $this->get('/api/hello');
        dump('test_example2');
        $response->assertStatus(200);
    }
}
