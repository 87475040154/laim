<?php

namespace Database\Factories\Ads;

use App\Models\Ads\Kvartira;
use Illuminate\Database\Eloquent\Factories\Factory;
use function fake;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kvartira>
 */
class KvartiraFactory extends Factory
{

    protected $model = Kvartira::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'author_id' => fake()->randomElement($array = array (1,2,3,4)),
            'tip_obekta' => 'Квартира',
            'table_name' => 'Kvartira',
            'zagolovok'=> fake()->randomElement($array = array ('Сдам 1 комнатную квартиру в цетре','Сдам 2х комнатную квартиру', 'Сдам 3 комнатную', 'Сдам квартиру 4 комнаты на длительно')),
            'period_arendi'=> fake()->randomElement($array = array ('На длительно','По суточно','По часам', 'На подселение')),
            'kolichestvo_komnat' => fake()->randomDigitNotNull(),
            'cena' => fake()->numberBetween($min = 1, $max = 300) * 1000,
            'etag' => fake()->randomElement($array = array (1,2,3,4)),
            'etagnost' => fake()->randomElement($array = array (5,9)),
            'ploshad_obshaya' => fake()->numberBetween($min = 30, $max = 300),
            'ploshad_kuhni' => fake()->numberBetween($min = 6, $max = 20),
            'tip_stroeniya'=> fake()->randomElement($array = array ('Панель','Кирпич','Дерево', 'Другое')),
            'oblast'=> fake()->randomElement($array = array ('Северо-Казахстанская обл.','Алматы','Костанайская обл.', 'Атырауская обл.')),
            'gorod' => function (array $attributes) {
                // Используйте значения из 'oblast' для связи с соответствующими городами
                switch ($attributes['oblast']) {
                    case 'Северо-Казахстанская обл.':
                        return fake()->randomElement(['Петропавловск']);
                    case 'Алматы':
                        return fake()->randomElement(['Алматы']);
                    case 'Костанайская обл.':
                        return fake()->randomElement(['Костанай']);
                    case 'Атырауская обл.':
                        return fake()->randomElement(['Атырау']);
                    default:
                        return fake()->randomElement(['Другой город по умолчанию']);
                }
            },
            'lat'=> '54.'.fake()->numberBetween($min = 10, $max = 5000),
            'lon'=> '69.'.fake()->numberBetween($min = 10, $max = 5000),
            'ulica'=> fake()->randomElement($array = array ('Нурсултана Назарбаева','Жамбыла Жабаева','Ауэзова', 'Абая', 'Сейфулина', 'Жумабаева')),
            'nomer_doma'=> fake()->numberBetween($min = 1, $max = 200),
            'images'=> [fake()->randomElement($array = array ('1.jpg','2.jpg','3.jpg','4.jpg','5.jpg','6.jpg','7.jpg','8.jpg','9.jpg','10.jpg',))],
            'sostoyanie'=> fake()->randomElement($array = array ('Евро ремонт','Хорошее','Среднее', 'Требует ремонта')),
            'mebel'=> fake()->randomElement($array = array ('Полностью','Частично','Без мебели')),
            'internet'=> fake()->randomElement($array = array ('Модем','Проводной','Нет')),
            'balkon'=> fake()->randomElement($array = array ('Балкон','Лоджия','Нет')),
            'sanuzel'=> fake()->randomElement($array = array ('Раздельный','Совмещенный','Нет')),
            'parkovka'=> fake()->randomElement($array = array ('Паркинг','Гараж')),
            'pol'=> fake()->randomElement($array = array ('Линолеум','Паркет','Ламинат')),
            'mebel_arr'=> ['Холодильник','Стиральная', 'машина','телевизор','диван','кровать'],
            'raznoe'=> ['Кухня-студия','Кладовка', 'комнаты', 'изолированы'],
            'bezopasnost'=> ['Домофон','видеонаблюдение' ,'видеодомофон'],
            'text_obyavleniya'=> fake()->text($maxNbChars = 1500),
            'ownerOrRealtor'=> fake()->randomElement($array = array ('Хозяин','Риелтор')),
            'name'=> fake()->randomElement($array = array ('Исмаил','Алена','Руслан','Артём')),
            'tel'=> fake()->regexify("^[0-9]{3} [0-9]{3} [0-9]{4}$"),
            'control'=> 'Активно',
            'view'=> fake()->numberBetween($min = 30, $max = 30000),
            'viewTel'=> fake()->numberBetween($min = 30, $max = 30000),
            'countLike'=> fake()->numberBetween($min = 30, $max = 3000),
            'srochno_torg'=> fake()->randomElement([true, false, false, false,false,false]),
            'bueAds'=> '2020-12-12 12:12:12',
            'top'=> fake()->randomElement($array = array (fake()->dateTimeBetween($startDate = '-5 day', $endDate = 'now', $timezone = null), null, null, null, null, null, null)),
            'top_x7'=> fake()->randomElement($array = array (fake()->dateTimeBetween($startDate = '-5 day', $endDate = 'now', $timezone = null), null, null, null, null, null, null)),
            'top_x30'=> fake()->randomElement($array = array (fake()->dateTimeBetween($startDate = '-5 day', $endDate = 'now', $timezone = null), null, null, null, null, null, null)),
            'created_at' => fake()->dateTimeBetween($startDate = '-30 day', $endDate = 'now', $timezone = null) ,
            'updated_at' => fake()->dateTimeBetween($startDate = '-10 day', $endDate = 'now', $timezone = null)
        ];
    }
}
