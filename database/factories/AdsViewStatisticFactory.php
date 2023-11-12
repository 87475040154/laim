<?php

namespace Database\Factories;

use App\Models\AdsViewStatistic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdsViewStatistic>
 */
class AdsViewStatisticFactory extends Factory
{
    protected $model = AdsViewStatistic::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ads_id' => fake()->randomDigitNotNull(),
            'tip_obekta' => 'Квартира',
            'author_ip' => fake()->localIpv4()

        ];
    }
}
