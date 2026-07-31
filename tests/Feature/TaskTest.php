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

    public function testAnyoneCanSeeTasksIndex(): void
    {
        $task = Task::factory()->for($this->user, 'createdBy')->create();

        $this->get(route('tasks.index'))->assertStatus(200)->assertSee($task->name);
    }

    public function testAnyoneCanViewATask(): void
    {
        $task = Task::factory()->for($this->user, 'createdBy')->create();

        $this->get(route('tasks.show', $task))->assertStatus(200)->assertSee($task->name);
    }

    public function testGuestCannotOpenCreateForm(): void
    {
        $this->get(route('tasks.create'))->assertRedirect(route('login'));
    }

    public function testAuthenticatedUserCanOpenCreateForm(): void
    {
        $this->actingAs($this->user)->get(route('tasks.create'))->assertStatus(200);
    }

    public function testAuthenticatedUserCanStoreTask(): void
    {
        $response = $this->actingAs($this->user)->post(route('tasks.store'), [
            'name' => 'My task',
            'description' => 'desc',
            'status_id' => $this->status->id,
            'assigned_to_id' => null,
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'name' => 'My task',
            'status_id' => $this->status->id,
            'created_by_id' => $this->user->id,
        ]);
    }

    public function testStoreRequiresName(): void
    {
        $this->actingAs($this->user)
            ->post(route('tasks.store'), ['name' => '', 'status_id' => $this->status->id])
            ->assertSessionHasErrors('name');
    }

    public function testStoreRequiresExistingStatus(): void
    {
        $this->actingAs($this->user)
            ->post(route('tasks.store'), ['name' => 'x', 'status_id' => 999999])
            ->assertSessionHasErrors('status_id');
    }

    public function testAuthenticatedUserCanEditTask(): void
    {
        $task = Task::factory()->for($this->user, 'createdBy')->create();

        $this->actingAs($this->user)
            ->get(route('tasks.edit', $task))
            ->assertStatus(200)
            ->assertSee($task->name);
    }

    public function testAuthenticatedUserCanUpdateTask(): void
    {
        $task = Task::factory()->for($this->user, 'createdBy')->create();

        $this->actingAs($this->user)
            ->patch(route('tasks.update', $task), [
                'name' => 'updated',
                'status_id' => $this->status->id,
            ])
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'name' => 'updated']);
    }

    public function testOnlyCreatorCanDeleteTask(): void
    {
        $creator = User::factory()->create();
        $task = Task::factory()->for($creator, 'createdBy')->create();

        $this->actingAs($this->user)
            ->delete(route('tasks.destroy', $task))
            ->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function testCreatorCanDeleteOwnTask(): void
    {
        $task = Task::factory()->for($this->user, 'createdBy')->create();

        $this->actingAs($this->user)
            ->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function testAuthenticatedUserCanAttachLabelsWhenCreatingTask(): void
    {
        $label = Label::factory()->create();

        $this->actingAs($this->user)->post(route('tasks.store'), [
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

    public function testTaskShowDisplaysAttachedLabels(): void
    {
        $label = Label::factory()->create(['name' => 'urgent']);
        $task = Task::factory()->for($this->user, 'createdBy')->create();
        $task->labels()->sync([$label->id]);

        $this->get(route('tasks.show', $task))->assertSee('urgent');
    }

    public function testFilterByStatus(): void
    {
        $statusA = TaskStatus::factory()->create();
        $statusB = TaskStatus::factory()->create();
        Task::factory()->for($statusA, 'status')->for($this->user, 'createdBy')->create(['name' => 'match']);
        Task::factory()->for($statusB, 'status')->for($this->user, 'createdBy')->create(['name' => 'other']);

        $this->get(route('tasks.index', ['filter' => ['status_id' => $statusA->id]]))
            ->assertStatus(200)
            ->assertDontSee('other');
    }

    public function testFilterByAssignee(): void
    {
        $assignee = User::factory()->create();
        Task::factory()
            ->for($this->user, 'createdBy')
            ->create(['name' => 'assigned', 'assigned_to_id' => $assignee->id]);
        Task::factory()
            ->for($this->user, 'createdBy')
            ->create(['name' => 'unassigned', 'assigned_to_id' => null]);

        $this->get(route('tasks.index', ['filter' => ['assigned_to_id' => $assignee->id]]))
            ->assertStatus(200)
            ->assertSee('assigned')
            ->assertDontSee('unassigned');
    }

    public function testFilterByAuthor(): void
    {
        $creator = User::factory()->create();
        Task::factory()->for($creator, 'createdBy')->create(['name' => 'from-creator']);
        Task::factory()->for($this->user, 'createdBy')->create(['name' => 'from-other']);

        $this->get(route('tasks.index', ['filter' => ['created_by_id' => $creator->id]]))
            ->assertStatus(200)
            ->assertSee('from-creator')
            ->assertDontSee('from-other');
    }

    public function testEmptyFilterReturnsAllTasks(): void
    {
        Task::factory()->for($this->user, 'createdBy')->create(['name' => 'task-a']);
        Task::factory()->for($this->user, 'createdBy')->create(['name' => 'task-b']);

        $this->get(route('tasks.index', ['filter' => ['status_id' => '']]))
            ->assertStatus(200)
            ->assertSee('task-a')
            ->assertSee('task-b');
    }
}
