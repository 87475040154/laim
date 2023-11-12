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
            'author_id' => fake()->randomDigitNotNull(),
            'tip_obekta'=> fake()->randomElement($array = array ('Квартира','Общежитие','Дом', 'Дача', 'Офис', "Здание", "Магазин", "Промбаза", "Бутик")),
            'table_name'=> fake()->randomElement($array = array ('Квартиры','Общежития','Дома', 'Офиса')),
            'ads_id'=>fake()->numberBetween($min = 10, $max = 30000),
            'kolichestvo_komnat' => fake()->randomDigitNotNull(),
            'cena' => fake()->numberBetween($min = 1000, $max = 300000),
            'etag' => fake()->randomElement($array = array (1,2,3,4)),
            'etagnost' => fake()->randomElement($array = array (5,6,7,8,9)),
            'ploshad_obshaya' => fake()->numberBetween($min = 30, $max = 300),
            'cena_tip'=> fake()->randomElement($array = array ('За кв.м.','За все')),
            'period_arendi'=> fake()->randomElement($array = array ('На длительно','По суточно','По часам', 'На подселение')),
            'images'=> fake()->randomElement($array = array ('1.jpg','2.jpg','3.jpg','4.jpg','5.jpg','6.jpg','7.jpg','8.jpg','9.jpg','10.jpg',)),
            'oblast'=> fake()->randomElement($array = array ('Северо-Казахстанская','Алматинская','Костанайская', 'Атырауская')),
            'gorod'=> fake()->randomElement($array = array ('Петропавловск','Алматы','Костанай', 'Атырау')),
            'lat'=> '54.'.fake()->numberBetween($min = 10, $max = 5000),
            'lon'=> '69.'.fake()->numberBetween($min = 10, $max = 5000),
            'ulica'=> fake()->randomElement($array = array ('Мира','Жабаева','Алматинская', 'Букетова')),
            'nomer_doma'=> fake()->randomElement($array = array ('5А','44Б','13В', '7с')),
            'ownerOrRealtor'=> fake()->randomElement($array = array ('Хозяин','Риелтор')),
            'control'=> 'Активно',
            'created_at' => fake()->dateTimeBetween($startDate = '-30 day', $endDate = 'now', $timezone = null) ,
        ];
    }
}
