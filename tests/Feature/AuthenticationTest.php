<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected User $testUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->testUser = User::create([
            'name' => 'UTBK Student',
            'username' => 'admin',
            'email' => 'admin@utbktracker.local',
            'password' => Hash::make('password123'),
        ]);
    }

    /** 1. Buka / tanpa login -> /login */
    public function test_root_without_login_redirects_to_login()
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    /** 2. Buka /dashboard tanpa login -> /login */
    public function test_dashboard_without_login_redirects_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /** 3. Username kosong -> validation error */
    public function test_empty_username_validation_error()
    {
        $response = $this->post('/login', [
            'username' => '',
            'password' => 'password123',
        ]);
        $response->assertSessionHasErrors(['username']);
    }

    /** 4. Password kosong -> validation error */
    public function test_empty_password_validation_error()
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => '',
        ]);
        $response->assertSessionHasErrors(['password']);
    }

    /** 5. Credential salah -> error credential */
    public function test_wrong_credentials_returns_error()
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'wrongpass',
        ]);
        $response->assertSessionHasErrors(['login' => 'Username atau password salah.']);
    }

    /** 6. Credential benar -> /dashboard */
    public function test_correct_credentials_redirects_to_dashboard()
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'password123',
        ]);
        $this->assertAuthenticatedAs($this->testUser);
        $response->assertRedirect('/dashboard');
    }

    /** 7. Setelah login buka /login -> /dashboard */
    public function test_authenticated_user_opening_login_redirects_to_dashboard()
    {
        $response = $this->actingAs($this->testUser)->get('/login');
        $response->assertRedirect('/dashboard');
    }

    /** 8. Logout -> /login */
    public function test_logout_redirects_to_login()
    {
        $response = $this->actingAs($this->testUser)->post('/logout');
        $this->assertGuest();
        $response->assertRedirect('/login');
    }

    /** 9. Setelah logout buka /dashboard -> /login */
    public function test_accessing_dashboard_after_logout_redirects_to_login()
    {
        $this->actingAs($this->testUser)->post('/logout');
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /** 10. Refresh setelah login -> tetap authenticated */
    public function test_refresh_after_login_remains_authenticated()
    {
        $this->actingAs($this->testUser);
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $this->assertAuthenticatedAs($this->testUser);
    }

    /** 11. Login dua kali -> tidak membuat session bermasalah */
    public function test_double_login_handles_session_safely()
    {
        $this->post('/login', [
            'username' => 'admin',
            'password' => 'password123',
        ]);
        $this->assertAuthenticatedAs($this->testUser);

        $response2 = $this->post('/login', [
            'username' => 'admin',
            'password' => 'password123',
        ]);
        $response2->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->testUser);
    }
}
