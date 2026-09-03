<?php

namespace Database\Seeders;

use App\Models\WearProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WearDemoSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Kipanya Crew T-Shirt', 'category' => 'T-Shirts', 'price' => 35000, 'badge' => 'Bestseller', 'image_path' => 'assets/wear/shirts/black.png', 'sort_order' => 1],
            ['name' => 'Stay Cool Monkey Tee', 'category' => 'T-Shirts', 'price' => 35000, 'badge' => 'New', 'image_path' => 'assets/wear/shirts/white.png', 'sort_order' => 2],
            ['name' => 'Kipanya Signature Hoodie', 'category' => 'Hoodies', 'price' => 75000, 'badge' => 'New', 'image_path' => 'assets/wear/shirts/rust.png', 'sort_order' => 3],
            ['name' => 'Kipanya Classic Cap', 'category' => 'Caps', 'price' => 25000, 'badge' => null, 'image_path' => 'assets/wear/shirts/black-clean.png', 'sort_order' => 4],
            ['name' => 'Dream Big Kids Tee', 'category' => 'Kids', 'price' => 30000, 'badge' => null, 'image_path' => 'assets/wear/shirts/teal.png', 'sort_order' => 5],
            ['name' => 'Kipanya Everyday Tee', 'category' => 'T-Shirts', 'price' => 32000, 'badge' => null, 'image_path' => 'assets/wear/shirts/navy.png', 'sort_order' => 6],
        ];

        foreach ($products as $product) {
            $record = WearProduct::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                $product + [
                    'description' => 'Premium Kipanya Wear made for everyday comfort, stories and style.',
                    'is_featured' => $product['sort_order'] <= 5,
                    'is_active' => true,
                ]
            );

            foreach (['S', 'M', 'L', 'XL', 'XXL'] as $size) {
                foreach (['black', 'white', 'navy'] as $color) {
                    $record->variants()->updateOrCreate(
                        ['size' => $size, 'color' => $color],
                        ['stock' => 12, 'sku' => strtoupper('KW-'.$record->id.'-'.$size.'-'.$color)]
                    );
                }
            }
        }
    }
}
