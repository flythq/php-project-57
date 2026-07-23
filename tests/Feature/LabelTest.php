<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_anyone_can_see_labels_index(): void
    {
        $label = Label::factory()->create();

        $this->get('/labels')->assertStatus(200)->assertSee($label->name);
    }

    public function test_guest_cannot_open_create_form(): void
    {
        $this->get('/labels/create')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_store_label(): void
    {
        $response = $this->actingAs($this->user)->post('/labels', [
            'name' => 'bug',
            'description' => 'something is wrong',
        ]);

        $response->assertRedirect('/labels');
        $this->assertDatabaseHas('labels', ['name' => 'bug', 'description' => 'something is wrong']);
    }

    public function test_store_requires_name(): void
    {
        $this->actingAs($this->user)
            ->post('/labels', ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_store_rejects_duplicate_name(): void
    {
        Label::factory()->create(['name' => 'bug']);

        $this->actingAs($this->user)
            ->post('/labels', ['name' => 'bug'])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('labels', 1);
    }

    public function test_update_allows_keeping_same_name(): void
    {
        $label = Label::factory()->create(['name' => 'bug']);

        $this->actingAs($this->user)
            ->patch("/labels/{$label->id}", ['name' => 'bug', 'description' => ''])
            ->assertRedirect('/labels');

        $this->assertDatabaseHas('labels', ['id' => $label->id, 'name' => 'bug']);
    }

    public function test_authenticated_user_can_update_label(): void
    {
        $label = Label::factory()->create(['name' => 'old']);

        $this->actingAs($this->user)
            ->patch("/labels/{$label->id}", ['name' => 'new', 'description' => ''])
            ->assertRedirect('/labels');

        $this->assertDatabaseHas('labels', ['id' => $label->id, 'name' => 'new']);
    }

    public function test_authenticated_user_can_delete_label(): void
    {
        $label = Label::factory()->create();

        $this->actingAs($this->user)
            ->delete("/labels/{$label->id}")
            ->assertRedirect('/labels');

        $this->assertDatabaseMissing('labels', ['id' => $label->id]);
    }

    public function test_label_linked_to_task_cannot_be_deleted(): void
    {
        $label = Label::factory()->create();
        $task = Task::factory()->for($this->user, 'createdBy')->create();
        $task->labels()->sync([$label->id]);

        $this->actingAs($this->user)
            ->delete("/labels/{$label->id}")
            ->assertRedirect('/labels');

        $this->assertDatabaseHas('labels', ['id' => $label->id]);
    }
}
