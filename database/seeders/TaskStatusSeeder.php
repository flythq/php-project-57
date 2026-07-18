<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Seed the initial task statuses.
     */
    public function run(): void
    {
        $names = [
            'новый',
            'завершен',
            'в работе',
            'на тестировании',
        ];

        foreach ($names as $name) {
            TaskStatus::firstOrCreate(['name' => $name]);
        }
    }
}
