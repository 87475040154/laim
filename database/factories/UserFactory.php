<?php

//1. php artisan make:factory UserFactory --model=User Создаем фабрику и заполняем
//2. php artisan make:seed UserTableSeeder Создаем сидр и заполняем
//3. php artisan db:seed --class=UserTableSeeder Запускаем сидр
//https://github.com/fzaninotto/Faker Здесь инфа про fake()

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('123123'), // password
            'tel'=> fake()->tollFreePhoneNumber(),
            'agree' => true
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
