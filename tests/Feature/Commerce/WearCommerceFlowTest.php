<?php

namespace Tests\Feature\Commerce;

use App\Models\WearProduct;
use App\Models\WearProductVariant;
use Database\Seeders\WearDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WearCommerceFlowTest extends TestCase
{
    use RefreshDatabase;

    private function product(): array
    {
        $product = WearProduct::create([
            'name' => 'Kipanya Crew Tee',
            'slug' => 'kipanya-crew-tee',
            'description' => 'Premium tee.',
            'category' => 'T-Shirts',
            'price' => 35000,
            'image_path' => 'assets/wear/shirts/black.png',
            'is_active' => true,
        ]);
        $variant = WearProductVariant::create([
            'wear_product_id' => $product->id,
            'size' => 'M',
            'color' => 'black',
            'stock' => 5,
            'sku' => 'KW-TEST-M-BLACK',
        ]);

        return [$product, $variant];
    }

    public function test_customer_can_add_variant_to_cart_and_open_checkout(): void
    {
        [$product, $variant] = $this->product();

        $this->post(route('wear.cart.items.store'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ])->assertRedirect();

        $this->get(route('wear.cart'))->assertOk()->assertSee('Kipanya Crew Tee')->assertSee('TSh 70,000');
        $this->get(route('wear.checkout'))->assertOk()->assertSee('Complete your order.');
    }

    public function test_storefront_hero_renders_an_active_catalog_product_image(): void
    {
        [$product] = $this->product();

        $this->get(route('wear'))
            ->assertOk()
            ->assertSee('FEATURED PRODUCT')
            ->assertSee($product->image_url, false);
    }

    public function test_seeded_storefront_uses_the_local_product_photos(): void
    {
        $this->seed(WearDemoSeeder::class);

        $this->assertDatabaseCount('wear_products', 14);
        $this->assertDatabaseHas('wear_products', [
            'slug' => 'pink-polo-shirt',
            'image_path' => 'assets/wear/catalog/pink-polo.jpg',
        ]);

        $this->get(route('wear'))
            ->assertSee('Pink Polo Shirt')
            ->assertSee('assets/wear/catalog/pink-polo.jpg', false)
            ->assertDontSee('images.unsplash.com');
    }

    public function test_customer_can_place_order_and_stock_is_decremented(): void
    {
        [$product, $variant] = $this->product();

        $this->post(route('wear.cart.items.store'), ['variant_id' => $variant->id, 'quantity' => 2]);
        $response = $this->post(route('wear.checkout.store'), [
            'customer_name' => 'Test Customer',
            'customer_phone' => '0712345678',
            'customer_email' => 'customer@example.com',
            'delivery_address' => '12 Sample Street',
            'delivery_city' => 'Dar es Salaam',
            'payment_method' => 'mobile_money',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('wear_orders', ['customer_phone' => '+255712345678', 'total' => 70000, 'status' => 'pending_payment']);
        $this->assertDatabaseHas('wear_order_items', ['product_name' => 'Kipanya Crew Tee', 'quantity' => 2]);
        $this->assertDatabaseHas('wear_product_variants', ['id' => $variant->id, 'stock' => 3]);
        $this->assertEquals(0, session('wear_cart', []) ? count(session('wear_cart')) : 0);
    }

    public function test_checkout_rejects_empty_cart(): void
    {
        $this->get(route('wear.checkout'))->assertRedirect(route('wear.cart'));
    }
}
