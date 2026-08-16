<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TKATryout;
use App\Models\TKASubject;
use App\Models\TKASubjectScore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TKAScoreTrackerTest extends TestCase
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

    private function createSampleTKATryout(string $name = 'TKA Tryout #1'): TKATryout
    {
        return TKATryout::create([
            'user_id' => $this->user->id,
            'name' => $name,
            'date' => '2026-08-16',
            'notes' => 'Catatan TKA.',
        ]);
    }

    public function test_user_can_access_tka_page(): void
    {
        $this->createSampleTKATryout();

        $response = $this->get('/tka');

        $response->assertStatus(200);
        $response->assertSee('TKA Analisis');
    }

    public function test_tka_subjects_seeded_with_23_official_subjects(): void
    {
        $this->assertDatabaseCount('tka_subjects', 23);
    }

    public function test_user_can_create_tka_tryout_with_smk_choice_subjects(): void
    {
        $mIndo = TKASubject::where('code', 'bahasa_indonesia')->first();
        $mMath = TKASubject::where('code', 'matematika')->first();
        $mEng = TKASubject::where('code', 'bahasa_inggris')->first();

        $cSmkPkk = TKASubject::where('code', 'smk_produk_projek_kreatif_kewirausahaan')->first();
        $cSmkKonsentrasi = TKASubject::where('code', 'konsentrasi_keahlian_smk')->first();

        $response = $this->post('/tka/tryouts', [
            'name' => 'TKA Tryout #3 (SMK)',
            'date' => '2026-08-25',
            'notes' => 'Simulasi TKA Khusus SMK.',
            'mandatory_scores' => [
                $mIndo->id => 85.00,
                $mMath->id => 78.00,
                $mEng->id => 75.00,
            ],
            'choice_1_id' => $cSmkPkk->id,
            'choice_1_score' => 88.00,
            'choice_2_id' => $cSmkKonsentrasi->id,
            'choice_2_score' => 92.50,
        ]);

        $response->assertRedirect('/tka');
        $this->assertDatabaseHas('tka_tryouts', [
            'name' => 'TKA Tryout #3 (SMK)',
        ]);
    }

    public function test_user_cannot_select_duplicate_choice_subjects(): void
    {
        $mIndo = TKASubject::where('code', 'bahasa_indonesia')->first();
        $mMath = TKASubject::where('code', 'matematika')->first();
        $mEng = TKASubject::where('code', 'bahasa_inggris')->first();
        $cFisika = TKASubject::where('code', 'fisika')->first();

        $response = $this->post('/tka/tryouts', [
            'name' => 'TKA Tryout Duplicate Test',
            'date' => '2026-08-25',
            'mandatory_scores' => [
                $mIndo->id => 80.00,
                $mMath->id => 70.00,
                $mEng->id => 70.00,
            ],
            'choice_1_id' => $cFisika->id,
            'choice_1_score' => 80.00,
            'choice_2_id' => $cFisika->id,
            'choice_2_score' => 75.00,
        ]);

        $response->assertSessionHasErrors(['choice_2_id']);
    }

    public function test_user_can_view_tka_tryout_details(): void
    {
        $tryout = $this->createSampleTKATryout();

        $response = $this->get("/tka/tryouts/{$tryout->id}");

        $response->assertStatus(200);
        $response->assertSee($tryout->name);
    }

    public function test_user_can_edit_tka_tryout_and_scores(): void
    {
        $tryout = $this->createSampleTKATryout();

        $mIndo = TKASubject::where('code', 'bahasa_indonesia')->first();
        $mMath = TKASubject::where('code', 'matematika')->first();
        $mEng = TKASubject::where('code', 'bahasa_inggris')->first();
        $cBiologi = TKASubject::where('code', 'biologi')->first();
        $cKimia = TKASubject::where('code', 'kimia')->first();

        $response = $this->put("/tka/tryouts/{$tryout->id}", [
            'name' => 'TKA Tryout #1 Revisi',
            'date' => '2026-08-16',
            'notes' => 'Nilai direvisi.',
            'mandatory_scores' => [
                $mIndo->id => 80.00,
                $mMath->id => 70.00,
                $mEng->id => 75.00,
            ],
            'choice_1_id' => $cBiologi->id,
            'choice_1_score' => 83.00,
            'choice_2_id' => $cKimia->id,
            'choice_2_score' => 76.00,
        ]);

        $response->assertRedirect('/tka');
        $this->assertDatabaseHas('tka_tryouts', [
            'id' => $tryout->id,
            'name' => 'TKA Tryout #1 Revisi',
        ]);
    }

    public function test_user_can_delete_tka_tryout_and_cascade_delete_subject_scores(): void
    {
        $tryout = $this->createSampleTKATryout();
        $tryoutId = $tryout->id;

        $response = $this->delete("/tka/tryouts/{$tryoutId}");

        $response->assertRedirect('/tka');
        $this->assertDatabaseMissing('tka_tryouts', ['id' => $tryoutId]);
    }
}
