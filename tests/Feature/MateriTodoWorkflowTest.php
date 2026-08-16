<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\Materi;
use App\Models\TodoTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MateriTodoWorkflowTest extends TestCase
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

    public function test_dashboard_renders_with_sidebar_and_materi_cards(): void
    {
        $subject = Subject::firstOrCreate(['name' => 'TKA Matematika']);
        Materi::create([
            'user_id' => $this->user->id,
            'subject_id' => $subject->id,
            'title' => 'Persamaan Linear',
        ]);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('UTBK Tracker');
        $response->assertSee('Dashboard');
        $response->assertSee('Persamaan Linear');
    }

    public function test_empty_state_rendered_when_no_materi_exists(): void
    {
        TodoTask::query()->delete();
        Materi::query()->delete();

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Belum Ada Materi Belajar', false);
    }

    public function test_user_can_create_new_materi(): void
    {
        $response = $this->post('/materi', [
            'subject_name' => 'Fisika Moderen',
            'title' => 'Trigonometri Lanjut',
            'description' => 'Sin, Cos, Tan dan Aturan Sinus/Cosinus.',
        ]);

        $this->assertDatabaseHas('subjects', [
            'name' => 'Fisika Moderen',
        ]);

        $this->assertDatabaseHas('materis', [
            'title' => 'Trigonometri Lanjut',
        ]);

        $materi = Materi::where('title', 'Trigonometri Lanjut')->first();
        $response->assertRedirect('/todolist?materi_id=' . $materi->id);
    }

    public function test_user_can_add_todo_task_for_materi(): void
    {
        $subject = Subject::firstOrCreate(['name' => 'Fisika']);
        $materi = Materi::create(['user_id' => $this->user->id, 'subject_id' => $subject->id, 'title' => 'Vektor']);

        $response = $this->post('/todolist', [
            'materi_id' => $materi->id,
            'title' => 'Latihan 20 Soal SBMPTN',
            'due_date' => '2026-09-01',
        ]);

        $response->assertRedirect('/todolist?materi_id=' . $materi->id);
        $this->assertDatabaseHas('todo_tasks', [
            'materi_id' => $materi->id,
            'title' => 'Latihan 20 Soal SBMPTN',
            'status' => 'pending',
        ]);
    }

    public function test_user_can_toggle_todo_status(): void
    {
        $subject = Subject::firstOrCreate(['name' => 'Biologi']);
        $materi = Materi::create(['user_id' => $this->user->id, 'subject_id' => $subject->id, 'title' => 'Sel']);
        $task = TodoTask::create(['user_id' => $this->user->id, 'materi_id' => $materi->id, 'title' => 'Tugas 1', 'status' => 'pending']);

        $response = $this->patch("/todolist/{$task->id}/complete");

        $task->refresh();
        $this->assertEquals('completed', $task->status);
    }

    public function test_user_can_delete_materi(): void
    {
        $subject = Subject::firstOrCreate(['name' => 'Kimia']);
        $materi = Materi::create(['user_id' => $this->user->id, 'subject_id' => $subject->id, 'title' => 'Stoikiometri']);

        $response = $this->delete("/materi/{$materi->id}");

        $response->assertRedirect('/materi');
        $this->assertDatabaseMissing('materis', ['id' => $materi->id]);
    }
}
