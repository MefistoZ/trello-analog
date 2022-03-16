<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DeskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Desk::factory()->count(50)->create();
    }
}