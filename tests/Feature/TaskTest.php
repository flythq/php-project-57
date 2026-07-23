<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private TaskStatus $status;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->status = TaskStatus::factory()->create();
    }

    public function test_anyone_can_see_tasks_index(): void
    {
        $task = Task::factory()->for($this->user, 'createdBy')->create();

        $this->get('/tasks')->assertStatus(200)->assertSee($task->name);
    }

    public function test_anyone_can_view_a_task(): void
    {
        $task = Task::factory()->for($this->user, 'createdBy')->create();

        $this->get("/tasks/{$task->id}")->assertStatus(200)->assertSee($task->name);
    }

    public function test_guest_cannot_open_create_form(): void
    {
        $this->get('/tasks/create')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_open_create_form(): void
    {
        $this->actingAs($this->user)->get('/tasks/create')->assertStatus(200);
    }

    public function test_authenticated_user_can_store_task(): void
    {
        $response = $this->actingAs($this->user)->post('/tasks', [
            'name' => 'My task',
            'description' => 'desc',
            'status_id' => $this->status->id,
            'assigned_to_id' => null,
        ]);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', [
            'name' => 'My task',
            'status_id' => $this->status->id,
            'created_by_id' => $this->user->id,
        ]);
    }

    public function test_store_requires_name(): void
    {
        $this->actingAs($this->user)
            ->post('/tasks', ['name' => '', 'status_id' => $this->status->id])
            ->assertSessionHasErrors('name');
    }

    public function test_store_requires_existing_status(): void
    {
        $this->actingAs($this->user)
            ->post('/tasks', ['name' => 'x', 'status_id' => 999999])
            ->assertSessionHasErrors('status_id');
    }

    public function test_authenticated_user_can_edit_task(): void
    {
        $task = Task::factory()->for($this->user, 'createdBy')->create();

        $this->actingAs($this->user)
            ->get("/tasks/{$task->id}/edit")
            ->assertStatus(200)
            ->assertSee($task->name);
    }

    public function test_authenticated_user_can_update_task(): void
    {
        $task = Task::factory()->for($this->user, 'createdBy')->create();

        $this->actingAs($this->user)
            ->patch("/tasks/{$task->id}", [
                'name' => 'updated',
                'status_id' => $this->status->id,
            ])
            ->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'name' => 'updated']);
    }

    public function test_only_creator_can_delete_task(): void
    {
        $creator = User::factory()->create();
        $task = Task::factory()->for($creator, 'createdBy')->create();

        $other = $this->user;
        $this->actingAs($other)->delete("/tasks/{$task->id}")->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function test_creator_can_delete_own_task(): void
    {
        $task = Task::factory()->for($this->user, 'createdBy')->create();

        $this->actingAs($this->user)
            ->delete("/tasks/{$task->id}")
            ->assertRedirect('/tasks');

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_authenticated_user_can_attach_labels_when_creating_task(): void
    {
        $label = Label::factory()->create();

        $this->actingAs($this->user)->post('/tasks', [
            'name' => 'With label',
            'description' => '',
            'status_id' => $this->status->id,
            'assigned_to_id' => null,
            'labels' => [$label->id],
        ]);

        $task = Task::where('name', 'With label')->first();
        $this->assertNotNull($task);
        $this->assertTrue($task->labels->contains($label));
    }

    public function test_task_show_displays_attached_labels(): void
    {
        $label = Label::factory()->create(['name' => 'urgent']);
        $task = Task::factory()->for($this->user, 'createdBy')->create();
        $task->labels()->sync([$label->id]);

        $this->get("/tasks/{$task->id}")->assertSee('urgent');
    }

    public function test_filter_by_status(): void
    {
        $statusA = TaskStatus::factory()->create();
        $statusB = TaskStatus::factory()->create();
        $taskA = Task::factory()->for($statusA, 'status')->for($this->user, 'createdBy')->create(['name' => 'match']);
        $taskB = Task::factory()->for($statusB, 'status')->for($this->user, 'createdBy')->create(['name' => 'other']);

        $this->get("/tasks?filter[status_id]={$statusA->id}")
            ->assertStatus(200)
            ->assertSee('match')
            ->assertDontSee('other');
    }

    public function test_filter_by_assignee(): void
    {
        $assignee = User::factory()->create();
        $matched = Task::factory()
            ->for($this->user, 'createdBy')
            ->create(['name' => 'assigned', 'assigned_to_id' => $assignee->id]);
        $other = Task::factory()
            ->for($this->user, 'createdBy')
            ->create(['name' => 'unassigned', 'assigned_to_id' => null]);

        $this->get("/tasks?filter[assigned_to_id]={$assignee->id}")
            ->assertStatus(200)
            ->assertSee('assigned')
            ->assertDontSee('unassigned');
    }

    public function test_filter_by_author(): void
    {
        $creator = User::factory()->create();
        $matched = Task::factory()->for($creator, 'createdBy')->create(['name' => 'from-creator']);
        $other = Task::factory()->for($this->user, 'createdBy')->create(['name' => 'from-other']);

        $this->get("/tasks?filter[created_by_id]={$creator->id}")
            ->assertStatus(200)
            ->assertSee('from-creator')
            ->assertDontSee('from-other');
    }

    public function test_empty_filter_returns_all_tasks(): void
    {
        $taskA = Task::factory()->for($this->user, 'createdBy')->create(['name' => 'task-a']);
        $taskB = Task::factory()->for($this->user, 'createdBy')->create(['name' => 'task-b']);

        $this->get('/tasks?filter[status_id]=')
            ->assertStatus(200)
            ->assertSee('task-a')
            ->assertSee('task-b');
    }
}
