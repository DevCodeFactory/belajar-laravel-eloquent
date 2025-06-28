<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            $image = new Image();
            $image->url = 'https://www.devcodefactory.com/customers/image/1.jpg';
            $image->imageable_id = 'FAHMI';
            $image->imageable_type = Customer::class;
            $image->save();
        }

        {
            $image = new Image();
            $image->url = 'https://www.devcodefactory.com/products/image/1.jpg';
            $image->imageable_id = '1';
            $image->imageable_type = Product::class;
            $image->save();
        }
    }
}
