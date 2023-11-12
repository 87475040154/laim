<?php

namespace Database\Seeders;

use App\Models\AdsViewStatistic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdsViewStatisticTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdsViewStatistic::factory()->count(10000)->create();
    }
}
