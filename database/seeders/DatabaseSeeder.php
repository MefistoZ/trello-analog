<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Desk::factory()->count(10)->create();
        \App\Models\DeskList::factory()->count(10)->create();
        \App\Models\Card::factory()->count(10)->create();
        \App\Models\Task::factory()->count(10)->create();
    }
}
