<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomErrorPagesAndModalsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'testuser@test.com',
            'role' => 'siswa',
            'password' => Hash::make('password123'),
        ]);
    }

    /** Test 1: Invalid URL returns custom 404 error page */
    public function test_invalid_url_returns_custom_404_page()
    {
        $response = $this->get('/invalid-route-path-that-does-not-exist');

        $response->assertStatus(404);
        $response->assertSee('404');
        $response->assertSee('Halaman Tidak Ditemukan');
        $response->assertSee('Kembali ke UTBK Tracker');
    }

    /** Test 2: Forbidden access returns custom 403 error page */
    public function test_forbidden_access_returns_custom_403_page()
    {
        // Non-admin student accessing admin PIN route
        $response = $this->actingAs($this->user)->get('/admin/pin');

        $response->assertStatus(403);
        $response->assertSee('403');
        $response->assertSee('Akses Ditolak');
    }

    /** Test 3: Confirmation modals do not contain any native confirm() onsubmit handlers */
    public function test_no_native_confirm_popups_exist_in_views()
    {
        $materiView = $this->actingAs($this->user)->get('/materi');
        $materiView->assertStatus(200);
        $materiView->assertDontSee('onsubmit="return confirm', false);
        $materiView->assertSee('form-confirm', false);

        $todoView = $this->actingAs($this->user)->get('/todolist');
        $todoView->assertStatus(200);
        $todoView->assertDontSee('onsubmit="return confirm', false);

        $utbkView = $this->actingAs($this->user)->get('/utbk');
        $utbkView->assertStatus(200);
        $utbkView->assertDontSee('onsubmit="return confirm', false);

        $tkaView = $this->actingAs($this->user)->get('/tka');
        $tkaView->assertStatus(200);
        $tkaView->assertDontSee('onsubmit="return confirm', false);
    }
}
