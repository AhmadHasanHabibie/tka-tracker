<?php

namespace Tests\Feature;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->user = User::first();
        $this->actingAs($this->user);
    }

    public function test_user_can_access_goal_page(): void
    {
        $response = $this->get('/tujuan');

        $response->assertStatus(200);
        $response->assertSee('Curahkan Target Impian Kamu');
    }

    public function test_user_can_store_and_update_goal(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('kampus.jpg');

        $response = $this->post('/tujuan', [
            'university_name' => 'Institut Teknologi Bandung',
            'study_program' => 'Teknik Elektro',
            'target_score' => 'Target 780+',
            'photo' => $file,
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('goals', [
            'university_name' => 'Institut Teknologi Bandung',
            'study_program' => 'Teknik Elektro',
            'target_score' => 'Target 780+',
        ]);

        $goal = Goal::where('user_id', $this->user->id)->first();
        $this->assertNotNull($goal->photo_path);
        Storage::disk('public')->assertExists($goal->photo_path);
    }

    public function test_dashboard_displays_motivation_banner(): void
    {
        Goal::create([
            'user_id' => $this->user->id,
            'university_name' => 'Universitas Indonesia',
            'study_program' => 'Ilmu Komputer',
            'target_score' => 'Target 760+',
        ]);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('TARGET UTBK & PTN IMPIAN', false);
        $response->assertSee('Universitas Indonesia');
    }
}
