<?php

use Illuminate\Database\Seeder;
use App\Place;

class PlacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $places = [
            [
                'id' => '1',
                'place_id' => '000',
                'name' => 'Danube Delta',
                'latitude' => '24.5234',
                'longitude' => '54.3781',
                'x' => '391',
                'y' => '247',
                'image_path' => '000.jpg',
                'description' => 'place0'
            ],
            [
                'id' => '2',
                'place_id' => '001',
                'name' => 'Al Bateen Beach',
                'latitude' => '24.4913',
                'longitude' => '54.4141',
                'x' => '503',
                'y' => '352',
                'image_path' => '001.jpg',
                'description' => 'place1'
            ],
            [
                'id' => '3',
                'place_id' => '002',
                'name' => 'Umm Al Emarat Park',
                'latitude' => '24.4567',
                'longitude' => '54.4238',
                'x' => '533',
                'y' => '465',
                'image_path' => '002.jpg',
                'description' => 'place2'
            ],
            [
                'id' => '4',
                'place_id' => '003',
                'name' => 'Murjan Splash Park',
                'latitude' => '24.4213',
                'longitude' => '54.4724',
                'x' => '684',
                'y' => '581',
                'image_path' => '003.jpg',
                'description' => 'place3'
            ],
            [
                'id' => '5',
                'place_id' => '004',
                'name' => 'Yas Waterworld',
                'latitude' => '24.4457',
                'longitude' => '54.5158',
                'x' => '819',
                'y' => '501',
                'image_path' => '004.jpg',
                'description' => 'place4'
            ],
            [
                'id' => '6',
                'place_id' => '005',
                'name' => 'Sheikh Zayed Bridge',
                'latitude' => '24.5103',
                'longitude' => '54.4952',
                'x' => '755',
                'y' => '290',
                'image_path' => '005.jpg',
                'description' => 'place5'
            ],
            [
                'id' => '7',
                'place_id' => '006',
                'name' => 'Manarat Al Saadiyat',
                'latitude' => '24.4604',
                'longitude' => '54.3185',
                'x' => '206',
                'y' => '453',
                'image_path' => '006.jpg',
                'description' => 'place6'
            ],
            [
                'id' => '8',
                'place_id' => '007',
                'name' => 'Saadiyat Island',
                'latitude' => '24.4702',
                'longitude' => '54.3739',
                'x' => '378',
                'y' => '421',
                'image_path' => '007.jpg',
                'description' => 'place7'
            ],
            [
                'id' => '9',
                'place_id' => '008',
                'name' => 'Bainunah Park',
                'latitude' => '24.4733',
                'longitude' => '54.4563',
                'x' => '634',
                'y' => '411',
                'image_path' => '008.jpg',
                'description' => 'place8'
            ],
            [
                'id' => '10',
                'place_id' => '009',
                'name' => 'Marina Eye',
                'latitude' => '24.4121',
                'longitude' => '54.6146',
                'x' => '1126',
                'y' => '611',
                'image_path' => '009.jpg',
                'description' => 'place9'
            ],
            [
                'id' => '11',
                'place_id' => '010',
                'name' => 'Corniche Beach',
                'latitude' => '24.4271',
                'longitude' => '54.4321',
                'x' => '559',
                'y' => '562',
                'image_path' => '010.jpg',
                'description' => 'place10'
            ]
        ];

        Place::insert($places);
    }
}
