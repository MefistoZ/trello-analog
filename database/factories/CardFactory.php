<?php

namespace Database\Factories;

use App\Models\DeskList;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle(),
            'desk_list_id' => DeskList::factory(),
//            'desk_lists_id' => DeskList::inRandomOrder()->first()->id,
        ];
    }
}
