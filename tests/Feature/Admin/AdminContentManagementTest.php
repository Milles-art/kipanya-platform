<?php

namespace Tests\Feature\Admin;

use App\Enums\ContentStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Cartoon;
use App\Models\User;
use App\Models\Collection;
use App\Models\CartoonEpisode;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
            ->assertJsonPath('data.categories', Category::count())
            ->assertJsonPath('data.cartoons', 0);
    }
    public function test_admin_can_open_cartoon_creation_form(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        Category::create(['name' => 'Kids', 'slug' => 'kids', 'is_active' => true]);

        $this->actingAs($admin, 'web')
            ->get('/admin/content/create')
            ->assertOk()
            ->assertSee('Create a cartoon.')
            ->assertSee('Content details')->assertSee('Collections');
    }


    public function test_admin_can_open_edit_cartoon_form(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $category = Category::create(['name' => 'Kids', 'slug' => 'kids', 'is_active' => true]);
        $cartoon = Cartoon::create([
            'category_id' => $category->id,
            'title' => 'Editable Story',
            'slug' => 'editable-story',
            'status' => ContentStatus::Draft,
        ]);

        $this->actingAs($admin, 'web')
            ->get("/admin/content/{$cartoon->id}/edit")
            ->assertOk()
            ->assertSee('Refine the story.')->assertSee('Collections')
            ->assertSee('Editable Story');
    }

    public function test_admin_can_update_cartoon_from_studio(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $category = Category::create(['name' => 'Kids', 'slug' => 'kids', 'is_active' => true]);
        $otherCategory = Category::create(['name' => 'Family Stories', 'slug' => 'family-stories', 'is_active' => true]);
        $cartoon = Cartoon::create([
            'category_id' => $category->id,
            'title' => 'Old Story',
            'slug' => 'old-story',
            'status' => ContentStatus::Draft,
        ]);

        $this->actingAs($admin, 'web')
            ->put("/admin/content/{$cartoon->id}", [
                'category_id' => $otherCategory->id,
                'title' => 'Updated Story',
                'slug' => 'updated-story',
                'description' => 'Updated description.',
                'status' => 'published',
                'is_featured' => '1',
                'sort_order' => '4',
            ])
            ->assertRedirect("/admin/content/{$cartoon->id}")
            ->assertSessionHas('status');

        $cartoon->refresh();

        $this->assertSame('Updated Story', $cartoon->title);
        $this->assertSame('updated-story', $cartoon->slug);
        $this->assertSame($otherCategory->id, $cartoon->category_id);
        $this->assertSame('published', $cartoon->status->value);
        $this->assertTrue((bool) $cartoon->is_featured);
        $this->assertNotNull($cartoon->published_at);
    }

    public function test_admin_can_archive_cartoon(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $category = Category::create(['name' => 'Kids', 'slug' => 'kids', 'is_active' => true]);
        $cartoon = Cartoon::create([
            'category_id' => $category->id,
            'title' => 'Archive Story',
            'slug' => 'archive-story',
            'status' => ContentStatus::Published,
            'published_at' => now(),
        ]);

        $this->actingAs($admin, 'web')
            ->post("/admin/content/{$cartoon->id}/archive")
            ->assertRedirect("/admin/content/{$cartoon->id}");

        $this->assertDatabaseHas('cartoons', [
            'id' => $cartoon->id,
            'status' => 'archived',
        ]);
    }

    public function test_admin_can_create_cartoon_from_studio(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $category = Category::create(['name' => 'Kids', 'slug' => 'kids', 'is_active' => true]);

        $this->actingAs($admin, 'web')
            ->post('/admin/content', [
                'category_id' => $category->id,
                'title' => 'Studio Story',
                'slug' => 'studio-story',
                'description' => 'A story created from Studio.',
                'status' => 'draft',
                'is_featured' => '1',
                'sort_order' => '2',
            ])
            ->assertRedirect('/admin/content')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('cartoons', [
            'title' => 'Studio Story',
            'slug' => 'studio-story',
            'category_id' => $category->id,
            'status' => 'draft',
            'is_featured' => true,
            'sort_order' => 2,
        ]);
    }

    public function test_admin_can_upload_cartoon_artwork_from_studio(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $category = Category::create(['name' => 'Kids', 'slug' => 'kids', 'is_active' => true]);

        $this->actingAs($admin, 'web')
            ->post('/admin/content', [
                'category_id' => $category->id,
                'title' => 'Artwork Story',
                'slug' => 'artwork-story',
                'status' => 'draft',
                'thumbnail' => UploadedFile::fake()->createWithContent('story.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')),
            ])
            ->assertRedirect('/admin/content');

        $cartoon = Cartoon::where('slug', 'artwork-story')->firstOrFail();

        $this->assertNotNull($cartoon->thumbnail_path);
        Storage::disk('public')->assertExists($cartoon->thumbnail_path);
    }

    public function test_admin_can_replace_and_remove_cartoon_artwork(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $category = Category::create(['name' => 'Kids', 'slug' => 'kids', 'is_active' => true]);
        $cartoon = Cartoon::create([
            'category_id' => $category->id,
            'title' => 'Replace Story',
            'slug' => 'replace-story',
            'status' => ContentStatus::Draft,
        ]);

        $this->actingAs($admin, 'web')->put("/admin/content/{$cartoon->id}", [
            'category_id' => $category->id,
            'title' => $cartoon->title,
            'slug' => $cartoon->slug,
            'status' => 'draft',
            'thumbnail' => UploadedFile::fake()->createWithContent('first.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')),
        ])->assertRedirect("/admin/content/{$cartoon->id}");

        $cartoon->refresh();
        $firstPath = $cartoon->thumbnail_path;
        Storage::disk('public')->assertExists($firstPath);

        $this->actingAs($admin, 'web')->put("/admin/content/{$cartoon->id}", [
            'category_id' => $category->id,
            'title' => $cartoon->title,
            'slug' => $cartoon->slug,
            'status' => 'draft',
            'thumbnail' => UploadedFile::fake()->createWithContent('second.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')),
        ])->assertRedirect("/admin/content/{$cartoon->id}");

        $cartoon->refresh();
        $secondPath = $cartoon->thumbnail_path;
        $this->assertNotSame($firstPath, $secondPath);
        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($secondPath);

        $this->actingAs($admin, 'web')
            ->post("/admin/content/{$cartoon->id}/thumbnail/remove")
            ->assertRedirect("/admin/content/{$cartoon->id}/edit");

        $cartoon->refresh();
        $this->assertNull($cartoon->thumbnail_path);
        Storage::disk('public')->assertMissing($secondPath);
    }

    public function test_admin_can_manage_category_from_studio(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $category = Category::create(['name' => 'Kids', 'slug' => 'kids', 'is_active' => true]);

        $this->actingAs($admin, 'web')->get("/admin/categories/{$category->id}/edit")
            ->assertOk()->assertSee('Edit category.');

        $this->actingAs($admin, 'web')->put("/admin/categories/{$category->id}", [
            'name' => 'Family Stories', 'description' => 'Family cartoons', 'sort_order' => 3, 'is_active' => '1',
        ])->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Family Stories', 'slug' => 'family-stories', 'sort_order' => 3, 'is_active' => true]);
    }

    public function test_admin_can_create_and_manage_collection_from_studio(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $category = Category::create(['name' => 'Kids', 'slug' => 'kids', 'is_active' => true]);
        $cartoon = Cartoon::create(['category_id' => $category->id, 'title' => 'Collection Story', 'slug' => 'collection-story', 'status' => ContentStatus::Draft]);

        $this->actingAs($admin, 'web')->post('/admin/collections', [
            'name' => 'Best Of', 'description' => 'Top stories',
        ])->assertRedirect();
        $collection = Collection::where('slug', 'best-of')->firstOrFail();

        $this->actingAs($admin, 'web')->put("/admin/collections/{$collection->id}", [
            'name' => 'Best Of', 'slug' => 'best-of', 'description' => 'Top stories', 'sort_order' => 1,
            'is_active' => '1', 'cartoon_ids' => [$cartoon->id],
        ])->assertRedirect("/admin/collections/{$collection->id}/edit");

        $this->assertTrue($collection->fresh()->cartoons()->whereKey($cartoon->id)->exists());

        Storage::fake('public');
        $this->actingAs($admin, 'web')->put("/admin/collections/{$collection->id}", [
            'name' => 'Best Of', 'slug' => 'best-of', 'description' => 'Top stories', 'sort_order' => 1,
            'is_active' => '1', 'cartoon_ids' => [$cartoon->id],
            'cover' => UploadedFile::fake()->createWithContent('cover.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')),
        ])->assertRedirect("/admin/collections/{$collection->id}/edit");
        $this->assertNotNull($collection->fresh()->cover_path);
        Storage::disk('public')->assertExists($collection->fresh()->cover_path);
    }

    public function test_admin_can_manage_cartoon_episode_from_studio(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $category = Category::create(['name' => 'Kids', 'slug' => 'kids', 'is_active' => true]);
        $cartoon = Cartoon::create(['category_id' => $category->id, 'title' => 'Series Story', 'slug' => 'series-story', 'status' => ContentStatus::Draft]);

        $this->actingAs($admin, 'web')->get("/admin/content/{$cartoon->id}/episodes/create")
            ->assertOk()->assertSee('Add episode.');

        $this->actingAs($admin, 'web')->post("/admin/content/{$cartoon->id}/episodes", [
            'episode_number' => 1, 'title' => 'Episode One', 'description' => 'First episode',
            'slug' => 'episode-one', 'youtube_video_id' => 'abc123', 'status' => 'published',
        ])->assertRedirect("/admin/content/{$cartoon->id}");

        $episode = CartoonEpisode::where('cartoon_id', $cartoon->id)->firstOrFail();
        $this->assertSame('published', $episode->status->value);
        $this->assertNotNull($episode->published_at);

        $this->actingAs($admin, 'web')->put("/admin/content/{$cartoon->id}/episodes/{$episode->id}", [
            'episode_number' => 1, 'title' => 'Episode One Updated', 'slug' => 'episode-one', 'status' => 'draft',
        ])->assertRedirect("/admin/content/{$cartoon->id}");
        $this->assertDatabaseHas('cartoon_episodes', ['id' => $episode->id, 'title' => 'Episode One Updated', 'status' => 'draft']);
    }

    public function test_scheduled_cartoon_is_published_by_command(): void
    {
        $category = Category::create(['name' => 'Kids', 'slug' => 'kids', 'is_active' => true]);
        $cartoon = Cartoon::create([
            'category_id' => $category->id, 'title' => 'Scheduled Story', 'slug' => 'scheduled-story',
            'status' => ContentStatus::Scheduled, 'published_at' => now()->subMinute(),
        ]);
        $episode = $cartoon->episodes()->create([
            'episode_number' => 1, 'title' => 'Scheduled Episode', 'slug' => 'scheduled-episode',
            'status' => ContentStatus::Scheduled, 'published_at' => now()->subMinute(),
        ]);

        Artisan::call('kipanya:publish-scheduled-cartoons');
        $this->assertSame('published', $cartoon->fresh()->status->value);
        $this->assertSame('published', $episode->fresh()->status->value);
    }

}
