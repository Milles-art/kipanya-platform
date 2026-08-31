<?php

namespace Tests\Feature\Admin;

use App\Enums\ContentStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Cartoon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_api(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $this->actingAs($user, 'sanctum')->getJson('/api/v1/admin/dashboard')->assertForbidden();
    }

    public function test_admin_can_create_and_publish_cartoon(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $category = Category::create(['name' => 'Kids', 'slug' => 'kids', 'is_active' => true]);

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/v1/admin/cartoons', [
            'category_id' => $category->id,
            'title' => 'Kipanya Adventure',
            'slug' => 'kipanya-adventure',
            'status' => 'draft',
        ]);

        $response->assertCreated()->assertJsonPath('data.status', ContentStatus::Draft->value);
        $cartoonId = $response->json('data.id');

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/admin/cartoons/{$cartoonId}/publish")
            ->assertOk()
            ->assertJsonPath('data.status', ContentStatus::Published->value);

        $this->assertDatabaseHas('cartoons', ['id' => $cartoonId, 'status' => 'published']);
    }

    public function test_admin_dashboard_returns_content_counts(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        Category::create(['name' => 'Kids', 'slug' => 'kids', 'is_active' => true]);

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/admin/dashboard')
            ->assertOk()
            ->assertJsonPath('data.categories', 1)
            ->assertJsonPath('data.cartoons', 0);
    }
}
