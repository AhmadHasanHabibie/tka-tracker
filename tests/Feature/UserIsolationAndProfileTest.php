<?php

namespace Tests\Feature;

use App\Models\Goal;
use App\Models\Materi;
use App\Models\Subject;
use App\Models\TodoTask;
use App\Models\User;
use App\Models\UTBKTryout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserIsolationAndProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $userA;
    protected User $userB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userA = User::create([
            'name' => 'User Alpha',
            'username' => 'user_a',
            'email' => 'user_a@test.com',
            'password' => Hash::make('password123'),
        ]);

        $this->userB = User::create([
            'name' => 'User Beta',
            'username' => 'user_b',
            'email' => 'user_b@test.com',
            'password' => Hash::make('password123'),
        ]);
    }

    /** Test 1: User data is completely isolated and not visible to other users */
    public function test_user_data_is_isolated_between_users()
    {
        $subject = Subject::create(['name' => 'TKA Matematika']);

        // User A creates Goal & Materi
        $goalA = Goal::create([
            'user_id' => $this->userA->id,
            'university_name' => 'ITB',
            'study_program' => 'Teknik Informatika',
        ]);

        $materiA = Materi::create([
            'user_id' => $this->userA->id,
            'subject_id' => $subject->id,
            'title' => 'Kalkulus Lanjut',
        ]);

        TodoTask::create([
            'user_id' => $this->userA->id,
            'materi_id' => $materiA->id,
            'title' => 'Kerjakan Latihan 1',
            'status' => 'pending',
        ]);

        // User B views Dashboard -> should NOT see User A's goal or materi
        $response = $this->actingAs($this->userB)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertDontSee('ITB');
        $response->assertDontSee('Kalkulus Lanjut');

        // User B views Materi index -> should NOT see User A's materi
        $materiResponse = $this->actingAs($this->userB)->get('/materi');
        $materiResponse->assertStatus(200);
        $materiResponse->assertDontSee('Kalkulus Lanjut');
    }

    /** Test 2: User B cannot edit or delete User A's materi or tryout */
    public function test_user_cannot_access_or_modify_other_user_records()
    {
        $subject = Subject::create(['name' => 'TKA Fisika']);

        $materiA = Materi::create([
            'user_id' => $this->userA->id,
            'subject_id' => $subject->id,
            'title' => 'Fisika Kuantum',
        ]);

        // User B tries to edit User A's materi -> 403 Forbidden
        $responseEdit = $this->actingAs($this->userB)->get("/materi/{$materiA->id}/edit");
        $responseEdit->assertStatus(403);

        // User B tries to delete User A's materi -> 403 Forbidden
        $responseDelete = $this->actingAs($this->userB)->delete("/materi/{$materiA->id}");
        $responseDelete->assertStatus(403);
    }

    /** Test 3: Profile info update (Name & Username) */
    public function test_user_can_update_profile_info()
    {
        $response = $this->actingAs($this->userA)->put('/profile/info', [
            'name' => 'User Alpha Updated',
            'username' => 'new_alpha',
        ]);

        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('users', [
            'id' => $this->userA->id,
            'name' => 'User Alpha Updated',
            'username' => 'new_alpha',
        ]);
    }

    /** Test 4: Password update */
    public function test_user_can_change_password()
    {
        $response = $this->actingAs($this->userA)->put('/profile/password', [
            'current_password' => 'password123',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertRedirect('/profile');
        $this->assertTrue(Hash::check('newpassword456', $this->userA->fresh()->password));
    }
}
