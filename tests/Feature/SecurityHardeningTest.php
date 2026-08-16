<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_rate_limiting_prevents_brute_force(): void
    {
        User::create([
            'name' => 'Test Security User',
            'username' => 'securitytest',
            'email' => 'security@example.com',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
        ]);

        // Fail 5 times
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/login', [
                'username' => 'securitytest',
                'password' => 'wrongpassword',
            ]);
            $response->assertSessionHasErrors(['login']);
        }

        // 6th attempt should trigger rate limit message
        $response = $this->post('/login', [
            'username' => 'securitytest',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertStringContainsString('Terlalu banyak percobaan login gagal', session('errors')->first('login'));
    }

    public function test_security_headers_are_attached(): void
    {
        $response = $this->get('/login');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
    }
}
