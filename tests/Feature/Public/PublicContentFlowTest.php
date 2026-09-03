<?php

namespace Tests\Feature\Public;

use App\Enums\ContentStatus;
use App\Models\Cartoon;
use App\Models\Category;
use App\Models\Collection;
use App\Models\OtpCode;
use App\Models\User;
use App\Enums\OtpPurpose;
use Illuminate\Support\Facades\Hash;
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
            'thumbnail_url' => '/assets/cartoon/demo/cartoon-01.jpg',
            'artwork_format' => 'landscape',
        ], $attributes));
    }

    public function test_home_and_discover_show_published_content_only(): void
    {
        $published = $this->publishedCartoon(['title' => 'Published Story']);
        $draftCategory = Category::create(['name' => 'Drafts', 'slug' => 'drafts-'.uniqid(), 'is_active' => true, 'sort_order' => 0]);
        Cartoon::create(['category_id' => $draftCategory->id, 'title' => 'Draft Story', 'slug' => 'draft-story-'.uniqid(), 'status' => ContentStatus::Draft]);

        $this->get('/')->assertOk();
        $this->get('/discover')->assertOk()->assertSee('Published Story');
    }

    public function test_discover_can_search_and_filter_by_category(): void
    {
        $category = Category::create(['name' => 'Adventure', 'slug' => 'adventure', 'is_active' => true]);
        Cartoon::create(['category_id' => $category->id, 'title' => 'Ocean Quest', 'slug' => 'ocean-quest', 'status' => ContentStatus::Published, 'published_at' => now(), 'thumbnail_url' => '/assets/cartoon/demo/cartoon-01.jpg', 'artwork_format' => 'landscape']);
        $other = Category::create(['name' => 'Nature', 'slug' => 'nature', 'is_active' => true]);
        Cartoon::create(['category_id' => $other->id, 'title' => 'Quiet Garden', 'slug' => 'quiet-garden', 'status' => ContentStatus::Published, 'published_at' => now(), 'thumbnail_url' => '/assets/cartoon/demo/cartoon-02.jpg', 'artwork_format' => 'landscape']);

        $this->get('/discover?q=Ocean')->assertOk()->assertSee('Ocean Quest')->assertDontSee('Quiet Garden');
        $this->get('/discover?category=adventure')->assertOk()->assertSee('Ocean Quest')->assertDontSee('Quiet Garden');
    }

    public function test_cartoon_detail_is_artwork_only_and_has_no_episode_ui(): void
    {
        $cartoon = $this->publishedCartoon([
            'title' => 'Kipanya Artwork',
            'caption' => 'A memorable line.',
            'thumbnail_url' => '/assets/cartoon/demo/cartoon-01.jpg',
            'artwork_format' => 'landscape',
        ]);

        $this->get('/cartoon/'.$cartoon->slug)
            ->assertOk()
            ->assertSee('Kipanya Artwork')
            ->assertSee('A memorable line.')
            ->assertSee('Make this a T-shirt')
            ->assertDontSee('Episodes')
            ->assertDontSee('youtube.com/embed');

        $this->get('/watch/'.$cartoon->slug)->assertNotFound();
    }


    public function test_public_cartoon_archive_excludes_published_records_without_artwork(): void
    {
        $visible = $this->publishedCartoon(['title' => 'Visible Artwork']);
        Cartoon::create([
            'category_id' => $visible->category_id,
            'title' => 'Broken Public Cartoon',
            'slug' => 'broken-public-cartoon-'.uniqid(),
            'status' => ContentStatus::Published,
            'published_at' => now(),
        ]);

        $this->get('/cartoon/search')->assertOk()->assertSee('Visible Artwork')->assertDontSee('Broken Public Cartoon');
    }

    public function test_daily_cartoon_stories_show_newest_first_and_open_the_selected_artwork(): void
    {
        $today = $this->publishedCartoon([
            'title' => 'Today Cartoon',
            'is_daily' => true,
            'daily_date' => today(),
            'thumbnail_url' => '/assets/cartoon/demo/cartoon-01.jpg',
        ]);
        $yesterday = $this->publishedCartoon([
            'title' => 'Yesterday Cartoon',
            'is_daily' => true,
            'daily_date' => today()->subDay(),
            'thumbnail_url' => '/assets/cartoon/demo/cartoon-02.jpg',
        ]);

        $this->get('/cartoon')
            ->assertOk()
            ->assertSee('Daily Stories')
            ->assertSee('Browse Categories')
            ->assertSee('Favorites')
            ->assertSee('Ctrl')
            ->assertSee('Daily Cartoon')
            ->assertSee('Today Cartoon')
            ->assertSee('Yesterday Cartoon')->assertSee('data-story-viewer')->assertSee('data-story-next')->assertSee('data-story-progress')->assertDontSee('Discover thought-provoking');

        $this->get('/cartoon/'.$today->slug)->assertOk()->assertSee('Today Cartoon');
    }

    public function test_tshirt_designer_uses_published_cartoon_artwork(): void
    {
        $cartoon = $this->publishedCartoon([
            'title' => 'Wearable Story',
            'thumbnail_url' => '/assets/cartoon/demo/cartoon-01.jpg',
            'artwork_format' => 'landscape',
        ]);

        $this->get('/wear/from-cartoon/'.$cartoon->slug)
            ->assertOk()
            ->assertSee('T-shirt Designer')
            ->assertSee('/assets/cartoon/demo/cartoon-01.jpg')
            ->assertSee('/assets/wear/shirts/black.png')->assertDontSee('kipanya-black-shirt.png')->assertSee('Artwork preview paused for presentation')->assertSee('Choose another')->assertSee('data-artwork-enabled="false"', false);

        $this->post('/wear/from-cartoon/'.$cartoon->slug, [
            'color' => 'sand',
            'size' => 'L',
            'placement' => 'front-pocket',
            'scale' => 1,
            'offset_x' => 0,
            'offset_y' => 0,
            'rotation' => 0,
            'artwork_enabled' => 0,
        ])->assertRedirect('/wear/from-cartoon/'.$cartoon->slug);

        $this->assertSame('sand', session('wear_design.color'));
        $this->assertSame('L', session('wear_design.size'));
        $this->assertSame('front-pocket', session('wear_design.placement'));
        $this->assertFalse(session('wear_design.configuration.artwork_enabled'));
    }


    public function test_guest_wear_artwork_toggle_persists_in_session_across_refresh(): void
    {
        $cartoon = $this->publishedCartoon(['title' => 'Guest Wear Story']);

        $this->post('/wear/from-cartoon/'.$cartoon->slug.'/state', [
            'color' => 'black',
            'size' => 'M',
            'placement' => 'front-center',
            'artwork_enabled' => 0,
        ])->assertOk();

        $this->get('/wear/from-cartoon/'.$cartoon->slug)
            ->assertOk()
            ->assertSee('Artwork preview paused for presentation')
            ->assertSee('data-artwork-enabled="false"', false);
    }

    public function test_wear_artwork_toggle_persists_across_refresh(): void
    {
        $cartoon = $this->publishedCartoon(['title' => 'Persistent Wear Story']);
        $user = User::factory()->create(['status' => 'active']);

        $this->actingAs($user, 'web')->post('/wear/from-cartoon/'.$cartoon->slug.'/state', [
            'color' => 'white',
            'size' => 'M',
            'placement' => 'front-center',
            'artwork_enabled' => 0,
        ])->assertOk()->assertJson(['saved' => true, 'artwork_enabled' => false]);

        $this->actingAs($user, 'web')->get('/wear/from-cartoon/'.$cartoon->slug)
            ->assertOk()
            ->assertSee('Artwork preview paused for presentation')
            ->assertSee('data-artwork-enabled="false"', false);

        $this->assertDatabaseHas('wear_designs', [
            'user_id' => $user->id,
            'cartoon_id' => $cartoon->id,
            'color' => 'white',
            'size' => 'M',
            'placement' => 'front-center',
        ]);
    }

    public function test_search_includes_caption(): void
    {
        $cartoon = $this->publishedCartoon(['title' => 'Quiet Artwork', 'caption' => 'Special market day']);
        $this->get('/cartoon/search?q=market')->assertOk()->assertSee('Quiet Artwork');
    }

    public function test_authenticated_wear_design_is_persisted_as_a_real_design_configuration(): void
    {
        $cartoon = $this->publishedCartoon(['title' => 'Design Story']);
        $user = \App\Models\User::factory()->create(['status' => 'active']);

        $this->actingAs($user, 'web')->post('/wear/from-cartoon/'.$cartoon->slug, [
            'color' => 'teal',
            'size' => 'XL',
            'placement' => 'front-pocket',
            'scale' => 1.15,
            'offset_x' => 4,
            'offset_y' => -3,
            'rotation' => 2,
        ])->assertRedirect('/wear/from-cartoon/'.$cartoon->slug);

        $this->assertDatabaseHas('wear_designs', [
            'user_id' => $user->id,
            'cartoon_id' => $cartoon->id,
            'color' => 'teal',
            'size' => 'XL',
            'placement' => 'front-pocket',
        ]);
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
    public function test_client_can_register_with_web_otp_and_is_authenticated(): void
    {
        $phone = '+255712345678';

        $this->post('/account/register/request-otp', ['phone' => '0712 345 678'])
            ->assertRedirect()
            ->assertSessionHas('user_registration_phone', $phone)
            ->assertSessionHas('dev_otp_code');

        $otp = OtpCode::query()->where('phone', $phone)->where('purpose', OtpPurpose::Registration->value)->latest('id')->firstOrFail();
        $this->post('/account/register', ['name' => 'Millen', 'code' => $this->otpCode($otp)])
            ->assertRedirect(route('account'));

        $this->assertAuthenticatedAs(User::where('phone', $phone)->first(), 'web');
        $this->assertDatabaseHas('users', ['phone' => $phone, 'name' => 'Millen', 'status' => 'active']);
    }

    public function test_existing_client_can_login_with_web_otp_and_logout(): void
    {
        $user = User::factory()->create(['phone' => '+255713000001', 'status' => 'active']);

        $this->post('/account/login/request-otp', ['phone' => '0713 000 001'])
            ->assertRedirect()
            ->assertSessionHas('user_login_phone', '+255713000001')
            ->assertSessionHas('dev_otp_code');

        $otp = OtpCode::query()->where('phone', '+255713000001')->where('purpose', OtpPurpose::Login->value)->latest('id')->firstOrFail();
        $this->post('/account/login', ['code' => $this->otpCode($otp)])
            ->assertRedirect(route('account'));

        $this->assertAuthenticatedAs($user, 'web');
        $this->post('/account/logout')->assertRedirect(route('home'));
        $this->assertGuest('web');
    }

    public function test_public_collections_index_and_daily_filter_are_real_routes(): void
    {
        $collection = Collection::create(['name' => 'Editorial Picks', 'slug' => 'editorial-picks', 'is_active' => true, 'sort_order' => 0]);
        $daily = $this->publishedCartoon(['title' => 'Daily Pick', 'is_daily' => true, 'daily_date' => today()]);
        $collection->cartoons()->attach($daily->id, ['sort_order' => 1]);

        $this->get('/collections')->assertOk()->assertSee('Editorial Picks');
        $this->get('/cartoon/search?daily=1')->assertOk()->assertSee('Daily Pick');
    }

    private function otpCode(OtpCode $otp): string
    {
        // Tests use the local LogSmsGateway, so retrieve the generated code from the test log is intentionally avoided.
        // Instead, replace the hash with a deterministic code for the verification test.
        $code = '123456';
        $otp->update(['code_hash' => Hash::make($code)]);
        return $code;
    }

}
