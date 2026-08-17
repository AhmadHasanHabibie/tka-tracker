<?php

namespace Tests\Feature;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileStorageStreamTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->user = User::first();
    }

    public function test_authenticated_user_can_stream_avatar_from_storage(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');
        $path = Storage::disk('public')->putFile('avatars', $file);

        $this->user->update(['avatar' => $path]);

        $response = $this->actingAs($this->user)->get('/storage/' . $path);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/jpeg');
    }

    public function test_authenticated_user_can_stream_goal_photo_from_storage(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('goal.png');
        $path = Storage::disk('public')->putFile('goals', $file);

        $goal = Goal::create([
            'user_id' => $this->user->id,
            'university_name' => 'Universitas Indonesia',
            'study_program' => 'Ilmu Komputer',
            'photo_path' => $path,
        ]);

        $response = $this->actingAs($this->user)->get('/storage/' . $goal->photo_path);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $path = Storage::disk('public')->putFile('avatars', $file);

        $response = $this->get('/storage/' . $path);

        $response->assertRedirect('/login');
    }

    public function test_returns_404_when_file_does_not_exist(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->get('/storage/avatars/nonexistent.jpg');

        $response->assertStatus(404);
    }

    public function test_prevents_path_traversal_attempts(): void
    {
        Storage::fake('public');

        // Path traversal with double dots
        $response1 = $this->actingAs($this->user)->get('/storage/../.env');
        $response1->assertStatus(403);

        // Path traversal trying to escape storage/app/public
        $response2 = $this->actingAs($this->user)->get('/storage/../../config/app.php');
        $response2->assertStatus(403);

        // Path traversal encoded
        $response3 = $this->actingAs($this->user)->get('/storage/%2e%2e%2f.env');
        $response3->assertStatus(403);
    }

    public function test_avatar_upload_updates_user_and_old_file_is_deleted(): void
    {
        Storage::fake('public');

        $oldFile = UploadedFile::fake()->image('old_avatar.jpg');
        $oldPath = Storage::disk('public')->putFile('avatars', $oldFile);
        $this->user->update(['avatar' => $oldPath]);

        Storage::disk('public')->assertExists($oldPath);

        $newFile = UploadedFile::fake()->image('new_avatar.png');

        $response = $this->actingAs($this->user)->post('/profile/avatar', [
            'avatar' => $newFile,
        ]);

        $response->assertRedirect('/profile');

        $this->user->refresh();
        $this->assertNotEquals($oldPath, $this->user->avatar);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($this->user->avatar);

        // Verify newly uploaded file can be served immediately
        $streamResponse = $this->actingAs($this->user)->get('/storage/' . $this->user->avatar);
        $streamResponse->assertStatus(200);
    }
}
