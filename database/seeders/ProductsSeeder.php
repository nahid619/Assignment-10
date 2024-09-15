<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'sku' => 'SKU12345',
            'name' => 'Product One',
            'description' => 'Description for Product One',
            'upc' => 'UPC1234567890',
            'mpn' => 'MPN12345',
            'brand' => 'Brand A',
            'price' => 19.99,
        ]);

        Product::create([
            'sku' => 'SKU12346',
            'name' => 'Product Two',
            'description' => 'Description for Product Two',
            'upc' => 'UPC1234567891',
            'mpn' => 'MPN12346',
            'brand' => 'Brand B',
            'price' => 29.99,
        ]);
    }
}
