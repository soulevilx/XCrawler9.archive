<?php

namespace Modules\Jav\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OnejavFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Jav\Models\Onejav::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url' => '/torrent/abw326',
            'cover' => 'https://image.mgstage.com/images/prestige/abw/326/pb_e_abw-326.jpg',
            'dvd_id' => 'ABW-326',
            'size' => 10.00,
            'date' => '2023-02-09',
            'genres' => json_decode('["4HR+","AV Actress","Big Tits","Creampie","Deep Throating","Lotion","Solowork"]',true),
            'description' => 'Customs Tower PREMIUM ACT.05 Rich Cream Pie SEX Asuna Kawai',
            'performers' => json_decode('["Asuna Kawai"]',true),
            'torrent' => '/torrent/abw326/download/54947446/onejav.com_abw326.torrent'
        ];
    }
}

