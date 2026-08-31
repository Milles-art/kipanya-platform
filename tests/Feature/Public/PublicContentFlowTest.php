<?php

namespace Tests\Feature\Public;

use App\Enums\ContentStatus;
use App\Models\Cartoon;
use App\Models\CartoonEpisode;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicContentFlowTest extends TestCase
{
    use RefreshDatabase;

    private function publishedCartoon(array $attributes = []): Cartoon
    {
        $category = Category::create([
            'name' => 'General',
            'slug' => 'general-'.uniqid(),
            'is_active' => true,
            'sort_order' => 0,
        ]);

        return Cartoon::create(array_merge([
            'category_id' => $category->id,
            'title' => 'Sample Story',
            'slug' => 'sample-story-'.uniqid(),
            'status' => ContentStatus::Published,
            'published_at' => now(),
        ], $attributes));
    }

    public function test_home_and_discover_show_published_content_only(): void
    {
        $published = $this->publishedCartoon(['title' => 'Published Story']);
        $draftCategory = Category::create(['name' => 'Drafts', 'slug' => 'drafts-'.uniqid(), 'is_active' => true, 'sort_order' => 0]);
        Cartoon::create(['category_id' => $draftCategory->id, 'title' => 'Draft Story', 'slug' => 'draft-story-'.uniqid(), 'status' => ContentStatus::Draft]);

        $this->get('/')->assertOk()->assertSee('Published Story');
        $this->get('/discover')->assertOk()->assertSee('Published Story');
    }

    public function test_discover_can_search_and_filter_by_category(): void
    {
        $category = Category::create(['name' => 'Adventure', 'slug' => 'adventure', 'is_active' => true]);
        Cartoon::create(['category_id' => $category->id, 'title' => 'Ocean Quest', 'slug' => 'ocean-quest', 'status' => ContentStatus::Published, 'published_at' => now()]);
        $other = Category::create(['name' => 'Nature', 'slug' => 'nature', 'is_active' => true]);
        Cartoon::create(['category_id' => $other->id, 'title' => 'Quiet Garden', 'slug' => 'quiet-garden', 'status' => ContentStatus::Published, 'published_at' => now()]);

        $this->get('/discover?q=Ocean')->assertOk()->assertSee('Ocean Quest')->assertDontSee('Quiet Garden');
        $this->get('/discover?category=adventure')->assertOk()->assertSee('Ocean Quest')->assertDontSee('Quiet Garden');
    }

    public function test_watch_can_select_a_published_episode(): void
    {
        $cartoon = $this->publishedCartoon(['title' => 'Kipanya Story']);
        $episode = CartoonEpisode::create([
            'cartoon_id' => $cartoon->id,
            'title' => 'Episode Two',
                'slug' => 'episode-two',
            'episode_number' => 2,
            'status' => ContentStatus::Published,
            'youtube_video_id' => 'abc123',
        ]);

        $this->get('/watch/'.$cartoon->slug.'?episode='.$episode->slug)
            ->assertOk()
            ->assertSee('abc123')
            ->assertSee('Episode Two');
    }

    public function test_collection_page_shows_published_members(): void
    {
        $collection = Collection::create(['name' => 'Summer Stories', 'slug' => 'summer-stories', 'is_active' => true, 'sort_order' => 0]);
        $published = $this->publishedCartoon(['title' => 'Collection Story']);
        $draftCategory = Category::create(['name' => 'Unpublished', 'slug' => 'unpublished-'.uniqid(), 'is_active' => true]);
        $draft = Cartoon::create(['category_id' => $draftCategory->id, 'title' => 'Hidden Draft', 'slug' => 'hidden-draft-'.uniqid(), 'status' => ContentStatus::Draft]);
        $collection->cartoons()->attach($published->id, ['sort_order' => 1]);
        $collection->cartoons()->attach($draft->id, ['sort_order' => 2]);

        $this->get('/collections/'.$collection->slug)->assertOk()->assertSee('Collection Story')->assertDontSee($draft->title);
    }
}
