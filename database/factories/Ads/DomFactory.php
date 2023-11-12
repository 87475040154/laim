<?php

namespace Database\Factories\Ads;

use App\Models\Ads\Dom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ads\Dom>
 */
class DomFactory extends Factory
{
    protected $model = Dom::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'author_id' => fake()->randomDigitNotNull(),
            'tip_obekta'=> fake()->randomElement($array = array ('Дом','Дача')),
            'period_arendi'=> fake()->randomElement($array = array ('На длительно','По суточно','По часам', 'На подселение')),
            'kolichestvo_komnat' => fake()->randomDigitNotNull(),
            'cena' => fake()->numberBetween($min = 1000, $max = 300000),
            'etagnost' => fake()->randomElement($array = array (5,6,7,8,9)),
            'ploshad_obshaya' => fake()->numberBetween($min = 30, $max = 300),
            'ploshad_kuhni' => fake()->numberBetween($min = 6, $max = 20),
            'ploshad_uchastka' => fake()->numberBetween($min = 1, $max = 40),
            'tip_stroeniya'=> fake()->randomElement($array = array ('Панель','Кирпич','Дерево', 'Другое')),
            'oblast'=> fake()->randomElement($array = array ('Северо-Казахстанская обл.','Алматинская обл.','Костанайская обл.', 'Атырауская обл.')),
            'gorod'=> fake()->randomElement($array = array ('Петропавловск','Каскелен','Костанай', 'Атырау')),
            'lat'=> '54.'.fake()->numberBetween($min = 10, $max = 5000),
            'lon'=> '69.'.fake()->numberBetween($min = 10, $max = 5000),
            'ulica'=> fake()->randomElement($array = array ('Мира','Жабаева','Алматинская', 'Букетова')),
            'nomer_doma'=> fake()->randomElement($array = array ('5А','44Б','13В', '7с')),
            'images'=> fake()->randomElement($array = array ('1.jpg','2.jpg','3.jpg','4.jpg','5.jpg','6.jpg','7.jpg','8.jpg','9.jpg','10.jpg',)),
            'sostoyanie'=> fake()->randomElement($array = array ('Евро ремонт','Хорошее','Среднее', 'Требует ремонта')),
            'mebel'=> fake()->randomElement($array = array ('Полностью','Частично','Без мебели')),
            'internet'=> fake()->randomElement($array = array ('Модем','Проводной','Нет')),
            'sanuzel'=> fake()->randomElement($array = array ('В доме','2 с/у и более','На улице', "Нет")),
            'kanalizaciya'=> fake()->randomElement($array = array ('Центральная','Септик', "Нет")),
            'voda'=> fake()->randomElement($array = array ('Центральная','Скважина', "Нет")),
            'elektrichestvo'=> fake()->randomElement($array = array ('Есть', "Нет")),
            'otoplenie'=> fake()->randomElement($array = array ('Центральное',"На газе","На твердом топливе", "Нет")),
            'gaz'=> fake()->randomElement($array = array ('Магистральный',"Автономный","Нет")),
            'parkovka'=> fake()->randomElement($array = array ('Паркинг','Гараж')),
            'pol'=> fake()->randomElement($array = array ('Линолеум','Паркет','Ламинат')),
            'mebel_arr'=> 'Холодильник,Стиральная машина',
            'raznoe'=> 'Кухня-студия,Кладовка',
            'bezopasnost'=> 'Домофон',
            'text_obyavleniya'=> fake()->text($maxNbChars = 1500),
            'ownerOrRealtor'=> fake()->randomElement($array = array ('Хозяин','Риелтор')),
            'name'=> fake()->randomElement($array = array ('Исмаил','Алена','Руслан','Артём')),
            'tel'=> fake()->regexify("^[0-9]{3} [0-9]{3} [0-9]{4}$"),
            'raznoe'=> 'Сад,Баня,Бассейн',
            'control'=> 'Активно',
            'add_ads_top'=> fake()->randomElement($array = array (fake()->dateTimeBetween($startDate = '-6 day', $endDate = 'now', $timezone = null), null, null)),
            'top_x7'=> fake()->randomElement($array = array (fake()->dateTimeBetween($startDate = '-6 day', $endDate = 'now', $timezone = null), null, null)),
            'top_x30'=> fake()->randomElement($array = array (fake()->dateTimeBetween($startDate = '-15 day', $endDate = 'now', $timezone = null), null, null)),
            'created_at' => fake()->dateTimeBetween($startDate = '-30 day', $endDate = 'now', $timezone = null) ,
            'updated_at' => fake()->dateTimeBetween($startDate = '-30 day', $endDate = 'now', $timezone = null)
        ];

    }
}
