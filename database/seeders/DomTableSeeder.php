<?php

namespace Database\Seeders;

use App\Models\Ads\Dom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dom::factory()->count(100000)->create();
    }
}
