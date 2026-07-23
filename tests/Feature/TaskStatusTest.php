<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_anyone_can_see_statuses_index(): void
    {
        $status = TaskStatus::factory()->create();

        $this->get('/task_statuses')->assertStatus(200)->assertSee($status->name);
    }

    public function test_authenticated_user_can_see_statuses_index(): void
    {
        $status = TaskStatus::factory()->create();

        $response = $this->actingAs($this->user)->get('/task_statuses');

        $response->assertStatus(200);
        $response->assertSee($status->name);
    }

    public function test_authenticated_user_can_open_create_form(): void
    {
        $this->actingAs($this->user)->get('/task_statuses/create')->assertStatus(200);
    }

    public function test_authenticated_user_can_store_status(): void
    {
        $response = $this->actingAs($this->user)->post('/task_statuses', [
            'name' => 'в работе',
        ]);

        $response->assertRedirect('/task_statuses');
        $this->assertDatabaseHas('task_statuses', ['name' => 'в работе']);
    }

    public function test_store_requires_name(): void
    {
        $this->actingAs($this->user)
            ->post('/task_statuses', ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_store_rejects_duplicate_name(): void
    {
        TaskStatus::factory()->create(['name' => 'в работе']);

        $this->actingAs($this->user)
            ->post('/task_statuses', ['name' => 'в работе'])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('task_statuses', 1);
    }

    public function test_update_allows_keeping_same_name(): void
    {
        $status = TaskStatus::factory()->create(['name' => 'в работе']);

        $this->actingAs($this->user)
            ->patch("/task_statuses/{$status->id}", ['name' => 'в работе'])
            ->assertRedirect('/task_statuses');

        $this->assertDatabaseHas('task_statuses', ['id' => $status->id, 'name' => 'в работе']);
    }

    public function test_authenticated_user_can_open_edit_form(): void
    {
        $status = TaskStatus::factory()->create();

        $this->actingAs($this->user)
            ->get("/task_statuses/{$status->id}/edit")
            ->assertStatus(200)
            ->assertSee($status->name);
    }

    public function test_authenticated_user_can_update_status(): void
    {
        $status = TaskStatus::factory()->create(['name' => 'старый']);

        $this->actingAs($this->user)
            ->patch("/task_statuses/{$status->id}", ['name' => 'новый'])
            ->assertRedirect('/task_statuses');

        $this->assertDatabaseHas('task_statuses', ['id' => $status->id, 'name' => 'новый']);
    }

    public function test_authenticated_user_can_delete_status(): void
    {
        $status = TaskStatus::factory()->create();

        $this->actingAs($this->user)
            ->delete("/task_statuses/{$status->id}")
            ->assertRedirect('/task_statuses');

        $this->assertDatabaseMissing('task_statuses', ['id' => $status->id]);
    }

    public function test_status_linked_to_task_cannot_be_deleted(): void
    {
        $status = TaskStatus::factory()->create();
        Task::factory()->for($status, 'status')->create();

        $this->actingAs($this->user)
            ->delete("/task_statuses/{$status->id}")
            ->assertRedirect('/task_statuses');

        $this->assertDatabaseHas('task_statuses', ['id' => $status->id]);
    }
}
