<?php

namespace Database\Seeders;

use App\Models\WearProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WearDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Real-fashion image references for local/demo presentation.
        // Replace these URLs with Kipanya-owned photography before production.
        $products = [
            ['name' => 'Kipanya Classic Tee', 'category' => 'T-Shirts', 'price' => 35000, 'compare_at_price' => 42000, 'badge' => 'New', 'description' => 'A clean everyday cotton tee with an easy fit for workdays, weekends and everything in between.', 'image_path' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Signature Tee', 'category' => 'T-Shirts', 'price' => 38000, 'badge' => 'New', 'description' => 'The signature Kipanya tee: minimal from a distance, unmistakable up close.', 'image_path' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Oversize Tee', 'category' => 'T-Shirts', 'price' => 42000, 'badge' => null, 'description' => 'A relaxed silhouette built for a modern streetwear rotation.', 'image_path' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Graphic Tee', 'category' => 'T-Shirts', 'price' => 45000, 'badge' => 'Featured', 'description' => 'A statement graphic tee made for fans who wear the culture proudly.', 'image_path' => 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Essential Tee', 'category' => 'T-Shirts', 'price' => 32000, 'badge' => 'Best', 'description' => 'A dependable wardrobe staple with a soft hand feel and everyday styling.', 'image_path' => 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Street Hoodie', 'category' => 'Hoodies', 'price' => 65000, 'compare_at_price' => 78000, 'badge' => 'Best', 'description' => 'A heavyweight street-ready hoodie with a clean front and bold Kipanya attitude.', 'image_path' => 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Everyday Hoodie', 'category' => 'Hoodies', 'price' => 72000, 'badge' => 'New', 'description' => 'Soft layering made simple, with enough structure for city days and cool nights.', 'image_path' => 'https://images.unsplash.com/photo-1548883354-94bcfe321cbb?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Zip Hoodie', 'category' => 'Hoodies', 'price' => 78000, 'badge' => null, 'description' => 'A versatile zip-through hoodie designed for easy layering over tees and polos.', 'image_path' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Dad Cap', 'category' => 'Caps', 'price' => 25000, 'badge' => 'Best', 'description' => 'An everyday curved-brim cap that finishes the fit without trying too hard.', 'image_path' => 'https://images.unsplash.com/photo-1521369909029-2afed882baee?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya 5-Panel Cap', 'category' => 'Caps', 'price' => 28000, 'badge' => 'New', 'description' => 'A clean five-panel silhouette for a sharper streetwear look.', 'image_path' => 'https://images.unsplash.com/photo-1514327605112-b887c0e61c0a?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Canvas Tote', 'category' => 'Accessories', 'price' => 20000, 'badge' => null, 'description' => 'A practical canvas carryall for books, daily essentials and Kipanya finds.', 'image_path' => 'https://images.unsplash.com/photo-1591561954557-26941169b49e?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Everyday Backpack', 'category' => 'Accessories', 'price' => 68000, 'badge' => 'Featured', 'description' => 'A compact everyday backpack with a clean silhouette and room for your essentials.', 'image_path' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Long Sleeve', 'category' => 'Long Sleeves', 'price' => 48000, 'badge' => null, 'description' => 'A lightweight long sleeve for transitional weather and layered looks.', 'image_path' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Ribbed Long Sleeve', 'category' => 'Long Sleeves', 'price' => 52000, 'badge' => 'New', 'description' => 'A textured long sleeve with a close, comfortable fit for cooler days.', 'image_path' => 'https://images.unsplash.com/photo-1516822003754-cca485356ecb?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Kids Tee', 'category' => 'Kids Wear', 'price' => 28000, 'badge' => 'Kids', 'description' => 'A fun everyday tee for younger fans who want Kipanya in their rotation too.', 'image_path' => 'https://images.unsplash.com/photo-1519238263530-99bdd11df2ea?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Kids Hoodie', 'category' => 'Kids Wear', 'price' => 52000, 'badge' => 'Kids', 'description' => 'A cosy hoodie with a playful streetwear feel for younger Kipanya fans.', 'image_path' => 'https://images.unsplash.com/photo-1602293589930-45aad59ba3ab?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Denim Jacket', 'category' => 'Jackets', 'price' => 95000, 'badge' => 'Featured', 'description' => 'A timeless denim layer that adds texture and structure to simple everyday outfits.', 'image_path' => 'https://images.unsplash.com/photo-1523205565295-f0e8ef4cfa2b?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Utility Jacket', 'category' => 'Jackets', 'price' => 110000, 'compare_at_price' => 125000, 'badge' => 'Sale', 'description' => 'A utility-inspired jacket with practical pockets and a clean modern shape.', 'image_path' => 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?auto=format&fit=crop&w=1200&q=85'],
            ['name' => 'Kipanya Coach Jacket', 'category' => 'Jackets', 'price' => 88000, 'badge' => 'New', 'description' => 'A lightweight outer layer for evenings, travel and effortless street styling.', 'image_path' => 'https://images.unsplash.com/photo-1548883354-94bcfe321cbb?auto=format&fit=crop&w=1200&q=85'],
        ];

        foreach ($products as $index => $product) {
            $record = WearProduct::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                $product + [
                    'is_featured' => $index < 8,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );

            $variantSizes = match ($record->category) {
                'Caps', 'Accessories' => ['One Size'],
                'Kids Wear' => ['S', 'M', 'L'],
                default => ['S', 'M', 'L', 'XL', 'XXL'],
            };

            $variantColors = match ($record->category) {
                'Caps' => ['black', 'navy'],
                'Accessories' => ['black', 'natural'],
                'Kids Wear' => ['black', 'white', 'navy'],
                'Jackets' => ['black', 'blue', 'olive'],
                default => ['black', 'white', 'navy'],
            };

            foreach ($variantSizes as $size) {
                foreach ($variantColors as $color) {
                    $record->variants()->updateOrCreate(
                        ['size' => $size, 'color' => $color],
                        ['stock' => $record->category === 'Kids Wear' ? 8 : 12, 'sku' => strtoupper('KW-'.$record->id.'-'.$size.'-'.$color)]
                    );
                }
            }
        }
    }
}
