<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetTask()
    {
        $task = Task::factory()->create();

        $response = $this->get('/api/tasks/' . $task->id);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $task->id,
            'name' => $task->name,
        ]);
    }
}
