<?php

namespace Tests\Feature\Web;

use App\Models\Cartoon;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterfaceSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_home_and_discover_pages_render(): void
    {
        $category = Category::create(['name' => 'Stories', 'slug' => 'stories']);
        Cartoon::create([
            'category_id' => $category->id,
            'title' => 'Test Story',
            'slug' => 'test-story',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get('/')->assertOk()->assertSee('KIPANYA')->assertSee('Test Story');
        $this->get('/discover')->assertOk()->assertSee('Find your next favorite.');
    }

    public function test_admin_dashboard_requires_admin_session(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_dashboard_renders_for_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $this->actingAs($admin, 'web')->get('/admin')->assertOk()->assertSee('Recent content');
    }
}
