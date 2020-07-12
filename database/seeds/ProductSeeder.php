<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['BMW', 'Audi', 'Mercedes-Benz', 'Skoda', 'Volkswagen', 'Honda', 'Toyota', 'Hyundai', 'KIA', 'LADA', 'Ford', 'Jaguar', 'Aston Martin', 'Opel', 'Porsche', 'Renault', 'Peugeot', 'Citroen', 'UAZ'];
        $colors = ['Amaranth', 'Amber', 'Amethyst', 'Aquamarine', 'Azure', 'Beige', 'Black', 'Blue', 'Blush', 'Bronze', 'Brown', 'Cerulean', 'Coffee', 'Coral', 'Cyan', 'Gold', 'Gray', 'Green', 'Indigo', 'Lemon', 'Maroon', 'Red', 'Silver', 'Yellow', 'White'];

        $i = 0;
        do {
            DB::table('products')->insert([
                'name' => $names[array_rand($names, 1)],
                'price' => rand(1000000, 10000000),
                'quantity' => rand(1, 100),
            ]);
            $i++;
        } while ($i < 500);

    }
}
