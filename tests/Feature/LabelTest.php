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

    public function testAnyoneCanSeeLabelsIndex(): void
    {
        $label = Label::factory()->create();

        $this->get(route('labels.index'))->assertStatus(200)->assertSee($label->name);
    }

    public function testGuestCannotOpenCreateForm(): void
    {
        $this->get(route('labels.create'))->assertRedirect(route('login'));
    }

    public function testAuthenticatedUserCanStoreLabel(): void
    {
        $response = $this->actingAs($this->user)->post(route('labels.store'), [
            'name' => 'bug',
            'description' => 'something is wrong',
        ]);

        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', ['name' => 'bug', 'description' => 'something is wrong']);
    }

    public function testStoreRequiresName(): void
    {
        $this->actingAs($this->user)
            ->post(route('labels.store'), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function testStoreRejectsDuplicateName(): void
    {
        Label::factory()->create(['name' => 'bug']);

        $this->actingAs($this->user)
            ->post(route('labels.store'), ['name' => 'bug'])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('labels', 1);
    }

    public function testUpdateAllowsKeepingSameName(): void
    {
        $label = Label::factory()->create(['name' => 'bug']);

        $this->actingAs($this->user)
            ->patch(route('labels.update', $label), ['name' => 'bug', 'description' => ''])
            ->assertRedirect(route('labels.index'));

        $this->assertDatabaseHas('labels', ['id' => $label->id, 'name' => 'bug']);
    }

    public function testAuthenticatedUserCanUpdateLabel(): void
    {
        $label = Label::factory()->create(['name' => 'old']);

        $this->actingAs($this->user)
            ->patch(route('labels.update', $label), ['name' => 'new', 'description' => ''])
            ->assertRedirect(route('labels.index'));

        $this->assertDatabaseHas('labels', ['id' => $label->id, 'name' => 'new']);
    }

    public function testAuthenticatedUserCanDeleteLabel(): void
    {
        $label = Label::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('labels.destroy', $label))
            ->assertRedirect(route('labels.index'));

        $this->assertDatabaseMissing('labels', ['id' => $label->id]);
    }

    public function testLabelLinkedToTaskCannotBeDeleted(): void
    {
        $label = Label::factory()->create();
        $task = Task::factory()->for($this->user, 'createdBy')->create();
        $task->labels()->sync([$label->id]);

        $this->actingAs($this->user)
            ->delete(route('labels.destroy', $label))
            ->assertRedirect(route('labels.index'));

        $this->assertDatabaseHas('labels', ['id' => $label->id]);
    }
}
