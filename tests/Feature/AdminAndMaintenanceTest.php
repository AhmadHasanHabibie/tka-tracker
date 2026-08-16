<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAndMaintenanceTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $studentUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Clear maintenance file if present
        $maintenanceFile = storage_path('app/maintenance.json');
        if (File::exists($maintenanceFile)) {
            File::delete($maintenanceFile);
        }

        $this->adminUser = User::create([
            'name' => 'Admin Test',
            'username' => 'admintest',
            'email' => 'admintest@test.com',
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        $this->studentUser = User::create([
            'name' => 'Siswa Test',
            'username' => 'siswatest',
            'email' => 'siswatest@test.com',
            'role' => 'siswa',
            'password' => Hash::make('password123'),
        ]);
    }

    /** Test 1: Student login gets clean workspace and redirects to dashboard */
    public function test_student_login_gets_clean_workspace()
    {
        $response = $this->post('/login', [
            'username' => 'siswatest',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->studentUser);

        $dashboard = $this->get('/dashboard');
        $dashboard->assertStatus(200);
        $dashboard->assertSee('Belum Ada Materi Belajar', false);
    }

    /** Test 2: Admin login redirects to PIN verification screen */
    public function test_admin_login_redirects_to_pin_screen()
    {
        $response = $this->post('/login', [
            'username' => 'admintest',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/pin');
        $this->assertAuthenticatedAs($this->adminUser);
    }

    /** Test 3: Wrong PIN fails, Correct PIN 131313 grants admin access */
    public function test_admin_pin_verification_flow()
    {
        // 1. Wrong PIN
        $wrongPinResponse = $this->actingAs($this->adminUser)->post('/admin/pin', [
            'pin' => '999999',
        ]);
        $wrongPinResponse->assertSessionHasErrors('pin');

        // 2. Correct PIN 131313
        $correctPinResponse = $this->actingAs($this->adminUser)->post('/admin/pin', [
            'pin' => '131313',
        ]);
        $correctPinResponse->assertRedirect('/admin/dashboard');

        // 3. Visiting admin dashboard after PIN verification works
        $adminDashboard = $this->actingAs($this->adminUser)->get('/admin/dashboard');
        $adminDashboard->assertStatus(200);
        $adminDashboard->assertSee('Dashboard Administrator');
    }

    /** Test 3b: Admin can download SQL database backup for phpMyAdmin */
    public function test_admin_can_download_sql_backup_for_phpmyadmin()
    {
        $response = $this->actingAs($this->adminUser)
            ->withSession(['admin_pin_verified' => true])
            ->get('/admin/backup/download');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/sql');
        $this->assertStringContainsString('.sql', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('UTBK Tracker Database SQL Backup', $response->getContent());
        $this->assertStringContainsString('INSERT INTO `users`', $response->getContent());
    }

    /** Test 4: Maintenance Mode toggle and student stuck prevention via Logout */
    public function test_maintenance_mode_and_logout_ability()
    {
        // Admin verifies PIN and toggles Maintenance ON
        $this->actingAs($this->adminUser)->withSession(['admin_pin_verified' => true]);
        $toggleResponse = $this->post('/admin/maintenance/toggle');
        $toggleResponse->assertRedirect();

        // Student tries to visit /dashboard -> receives 503 Maintenance page
        $studentResponse = $this->actingAs($this->studentUser)->get('/dashboard');
        $studentResponse->assertStatus(503);
        $studentResponse->assertSee('Website Sedang Perbaikan');
        $studentResponse->assertSee('Logout Sekarang');

        // Student performs POST /logout during maintenance -> successfully logs out without getting stuck!
        $logoutResponse = $this->actingAs($this->studentUser)->post('/logout');
        $logoutResponse->assertRedirect('/login');
        $this->assertGuest();

        // Clean up maintenance file
        $maintenanceFile = storage_path('app/maintenance.json');
        if (File::exists($maintenanceFile)) {
            File::delete($maintenanceFile);
        }
    }

    /** Test 5: Activity Logger records user login & logout events */
    public function test_activity_logger_records_actions()
    {
        $this->post('/login', [
            'username' => 'siswatest',
            'password' => 'password123',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'username' => 'siswatest',
            'action' => 'User Login',
        ]);
    }

    /** Test 6: Login page is accessible during maintenance mode; student sees maintenance overlay on dashboard, admin can enter with PIN */
    public function test_login_page_accessible_during_maintenance_and_differentiates_admin_from_student()
    {
        // Turn ON maintenance mode
        $this->actingAs($this->adminUser)->withSession(['admin_pin_verified' => true]);
        $this->post('/admin/maintenance/toggle');

        // Logout admin via HTTP request so test runner context is completely guest
        $this->post('/logout');

        // 1. Guest can access /login during maintenance
        $loginView = $this->get('/login');
        $loginView->assertStatus(200);
        $loginView->assertSee('UTBK Tracker');

        // 2. Student logs in during maintenance -> login succeeds (302 to /dashboard), but accessing /dashboard returns 503 Maintenance page
        $studentLogin = $this->post('/login', [
            'username' => 'siswatest',
            'password' => 'password123',
        ]);
        $studentLogin->assertRedirect('/dashboard');

        $studentDashboard = $this->actingAs($this->studentUser)->get('/dashboard');
        $studentDashboard->assertStatus(503);
        $studentDashboard->assertSee('Website Sedang Perbaikan');

        // Logout student and flush session
        $this->post('/logout');
        $this->flushSession();

        // 3. Admin logs in during maintenance -> login succeeds, redirects to PIN, verifies PIN 131313 -> enters Admin Dashboard
        $adminLogin = $this->post('/login', [
            'username' => 'admintest',
            'password' => 'password123',
        ]);
        $adminLogin->assertRedirect('/admin/pin');

        $verifyPin = $this->post('/admin/pin', ['pin' => '131313']);
        $verifyPin->assertRedirect('/admin/dashboard');

        $adminDashboard = $this->get('/admin/dashboard');
        $adminDashboard->assertStatus(200);
        $adminDashboard->assertSee('Dashboard Administrator');

        // Turn OFF maintenance mode after test
        $this->post('/admin/maintenance/toggle');
    }

    protected function tearDown(): void
    {
        $maintenanceFile = storage_path('app/maintenance.json');
        if (File::exists($maintenanceFile)) {
            File::delete($maintenanceFile);
        }
        parent::tearDown();
    }
}
