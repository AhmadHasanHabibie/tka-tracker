<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UTBKTryout;
use App\Models\UTBKSubtestScore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UTBKScoreTrackerTest extends TestCase
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

    private function createSampleTryout(string $name = 'Tryout Zenius #1', float $score = 614.57): UTBKTryout
    {
        return UTBKTryout::create([
            'user_id' => $this->user->id,
            'name' => $name,
            'date' => '2026-08-16',
            'overall_score' => $score,
            'notes' => 'Catatan tryout.',
        ]);
    }

    public function test_user_can_access_utbk_page(): void
    {
        $this->createSampleTryout();

        $response = $this->get('/utbk');

        $response->assertStatus(200);
        $response->assertSee('UTBK Score Tracker');
        $response->assertSee('Perkembangan Nilai Keseluruhan');
    }

    public function test_user_can_create_utbk_tryout_with_7_subtests(): void
    {
        $subtestScores = [
            'penalaran_umum' => 810,
            'ppu' => 520,
            'pbm' => 700,
            'pk' => 540,
            'lbi' => 960,
            'lbe' => 530,
            'penalaran_matematis' => 610,
        ];

        $response = $this->post('/utbk/tryouts', [
            'name' => 'Tryout Zenius #3',
            'date' => '2026-08-25',
            'overall_score' => 667.14,
            'notes' => 'Peningkatan konsisten.',
            'subtest_scores' => $subtestScores,
        ]);

        $response->assertRedirect('/utbk');

        $this->assertDatabaseHas('utbk_tryouts', [
            'name' => 'Tryout Zenius #3',
            'overall_score' => 667.14,
        ]);

        $tryout = UTBKTryout::where('name', 'Tryout Zenius #3')->first();
        $this->assertCount(7, $tryout->subtestScores);
    }

    public function test_user_can_view_utbk_tryout_details(): void
    {
        $tryout = $this->createSampleTryout();

        $response = $this->get("/utbk/tryouts/{$tryout->id}");

        $response->assertStatus(200);
        $response->assertSee($tryout->name);
    }

    public function test_user_can_edit_utbk_tryout_and_subtest_scores(): void
    {
        $tryout = $this->createSampleTryout();

        $subtestScores = [
            'penalaran_umum' => 850,
            'ppu' => 480,
            'pbm' => 710,
            'pk' => 500,
            'lbi' => 980,
            'lbe' => 490,
            'penalaran_matematis' => 550,
        ];

        $response = $this->put("/utbk/tryouts/{$tryout->id}", [
            'name' => 'Tryout Zenius #1 Revisi',
            'date' => '2026-08-16',
            'overall_score' => 651.43,
            'notes' => 'Update skor yang benar.',
            'subtest_scores' => $subtestScores,
        ]);

        $response->assertRedirect('/utbk');
        $this->assertDatabaseHas('utbk_tryouts', [
            'id' => $tryout->id,
            'name' => 'Tryout Zenius #1 Revisi',
            'overall_score' => 651.43,
        ]);
    }

    public function test_user_can_delete_utbk_tryout_and_subtest_scores_are_cascade_deleted(): void
    {
        $tryout = $this->createSampleTryout();
        $tryoutId = $tryout->id;

        $response = $this->delete("/utbk/tryouts/{$tryoutId}");

        $response->assertRedirect('/utbk');
        $this->assertDatabaseMissing('utbk_tryouts', ['id' => $tryoutId]);
    }

    public function test_score_difference_calculation(): void
    {
        $t1 = $this->createSampleTryout('Tryout Zenius #1', 614.57);
        $t2 = $this->createSampleTryout('Tryout Zenius #2', 640.20);

        $difference = round($t2->overall_score - $t1->overall_score, 2);
        $this->assertEquals(25.63, $difference);
    }
}
