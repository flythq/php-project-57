<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Seed the initial task labels.
     */
    public function run(): void
    {
        $names = [
            'баг',
            'фича',
            'вопрос',
            'документация',
            'дубликат',
        ];

        foreach ($names as $name) {
            Label::firstOrCreate(['name' => $name]);
        }
    }
}
