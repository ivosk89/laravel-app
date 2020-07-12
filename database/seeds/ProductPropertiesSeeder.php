<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductPropertiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = ['Amaranth', 'Amber', 'Amethyst', 'Aquamarine', 'Azure', 'Beige', 'Black', 'Blue', 'Blush', 'Bronze', 'Brown', 'Cerulean', 'Coffee', 'Coral', 'Cyan', 'Gold', 'Gray', 'Green', 'Indigo', 'Lemon', 'Maroon', 'Red', 'Silver', 'Yellow', 'White'];

        $i = 1;
        do {
            DB::table('products_properties')->insert([
                'product_id' => ($i+1),
                'property_name' => 'color',
                'property_value' => $colors[array_rand($colors, 1)],
            ]);
            $i++;
        } while ($i < 500);

        $i = 1;
        do {
            DB::table('products_properties')->insert([
                'product_id' => ($i+1),
                'property_name' => 'weight',
                'property_value' => rand(1000, 2500),
            ]);
            $i++;
        } while ($i < 500);

    }
}
