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

    public function testAnyoneCanSeeStatusesIndex(): void
    {
        $status = TaskStatus::factory()->create();

        $this->get(route('task_statuses.index'))->assertStatus(200)->assertSee($status->name);
    }

    public function testAuthenticatedUserCanSeeStatusesIndex(): void
    {
        $status = TaskStatus::factory()->create();

        $response = $this->actingAs($this->user)->get(route('task_statuses.index'));

        $response->assertStatus(200);
        $response->assertSee($status->name);
    }

    public function testAuthenticatedUserCanOpenCreateForm(): void
    {
        $this->actingAs($this->user)->get(route('task_statuses.create'))->assertStatus(200);
    }

    public function testAuthenticatedUserCanStoreStatus(): void
    {
        $response = $this->actingAs($this->user)->post(route('task_statuses.store'), [
            'name' => 'в работе',
        ]);

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', ['name' => 'в работе']);
    }

    public function testStoreRequiresName(): void
    {
        $this->actingAs($this->user)
            ->post(route('task_statuses.store'), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function testStoreRejectsDuplicateName(): void
    {
        TaskStatus::factory()->create(['name' => 'в работе']);

        $this->actingAs($this->user)
            ->post(route('task_statuses.store'), ['name' => 'в работе'])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('task_statuses', 1);
    }

    public function testUpdateAllowsKeepingSameName(): void
    {
        $status = TaskStatus::factory()->create(['name' => 'в работе']);

        $this->actingAs($this->user)
            ->patch(route('task_statuses.update', $status), ['name' => 'в работе'])
            ->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseHas('task_statuses', ['id' => $status->id, 'name' => 'в работе']);
    }

    public function testAuthenticatedUserCanOpenEditForm(): void
    {
        $status = TaskStatus::factory()->create();

        $this->actingAs($this->user)
            ->get(route('task_statuses.edit', $status))
            ->assertStatus(200)
            ->assertSee($status->name);
    }

    public function testAuthenticatedUserCanUpdateStatus(): void
    {
        $status = TaskStatus::factory()->create(['name' => 'старый']);

        $this->actingAs($this->user)
            ->patch(route('task_statuses.update', $status), ['name' => 'новый'])
            ->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseHas('task_statuses', ['id' => $status->id, 'name' => 'новый']);
    }

    public function testAuthenticatedUserCanDeleteStatus(): void
    {
        $status = TaskStatus::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('task_statuses.destroy', $status))
            ->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseMissing('task_statuses', ['id' => $status->id]);
    }

    public function testStatusLinkedToTaskCannotBeDeleted(): void
    {
        $status = TaskStatus::factory()->create();
        Task::factory()->for($status, 'status')->create();

        $this->actingAs($this->user)
            ->delete(route('task_statuses.destroy', $status))
            ->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseHas('task_statuses', ['id' => $status->id]);
    }
}
