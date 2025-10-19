<?php

namespace Database\Factories;

use App\Models\Like;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{

    protected $model = Like::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'author_id' => fake()->randomElement($array = array (1,2,3,4)),
            'likeable_type'=> fake()->randomElement($array = array ('App\Models\Ads\Kvartira','App\Models\Ads\Obshejitiya')),
            'likeable_id'=>fake()->numberBetween($min = 30, $max = 100000),
            'created_at' => fake()->dateTimeBetween($startDate = '-30 day', $endDate = 'now', $timezone = null) ,
            'updated_at' => fake()->dateTimeBetween($startDate = '-10 day', $endDate = 'now', $timezone = null)
            ];
    }
}
