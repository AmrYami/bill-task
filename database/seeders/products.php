<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class products extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'T-shirt',
                'description' => 'good item',
                'unit_price' => 10.99,
                'discount_percentage' => null,
                'user_id' => 1,
            ],
            [
                'name' => 'Pants',
                'description' => 'good item',
                'unit_price' => 14.99,
                'discount_percentage' => null,
                'user_id' => 1,
            ],
            [
                'name' => 'Jacket',
                'description' => 'good item',
                'unit_price' => 19.99,
                'discount_percentage' => null,
                'user_id' => 1,
            ],
            [
                'name' => 'Shoes',
                'description' => 'good item',
                'unit_price' => 24.99,
                'discount_percentage' => 10,
                'user_id' => 1,
            ],
        ];
        Product::insert($data);
    }
}
