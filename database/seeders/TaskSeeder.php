<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Seed the tasks.
     */
    public function run(): void
    {
        $statuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all();

        if ($statuses->isEmpty() || $users->isEmpty()) {
            return;
        }

        Task::factory()
            ->count(17)
            ->create([
                'status_id' => fn () => $statuses->random()->id,
                'created_by_id' => fn () => $users->random()->id,
                'assigned_to_id' => fn () => fake()->boolean(70) ? $users->random()->id : null,
            ])
            ->each(function (Task $task) use ($labels): void {
                if ($labels->isNotEmpty()) {
                    $task->labels()->attach(
                        $labels->random(random_int(1, min(3, $labels->count())))->pluck('id')
                    );
                }
            });
    }
}
