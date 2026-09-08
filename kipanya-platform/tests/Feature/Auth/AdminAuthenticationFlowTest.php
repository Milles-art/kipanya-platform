<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Integrations\Sms\SmsGateway;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_web_login_exposes_local_otp_and_creates_session(): void
    {
        $this->app->bind(SmsGateway::class, fn () => new class implements SmsGateway {
            public function send(string $phone, string $message): void {}
        });

        User::factory()->create([
            'phone' => '+255712345678',
            'role' => UserRole::Admin->value,
            'status' => UserStatus::Active->value,
        ]);

        $this->post('/admin/login/request-otp', ['phone' => '0712345678'])
            ->assertRedirect('/admin/login')
            ->assertSessionHas('admin_login_phone', '+255712345678')
            ->assertSessionHas('dev_otp_code');

        $code = session('dev_otp_code');

        $this->post('/admin/login', ['code' => $code])
            ->assertRedirect('/admin');

        $this->assertAuthenticated('web');
        $this->assertAuthenticatedAs(User::where('phone', '+255712345678')->first(), 'web');
    }
}
