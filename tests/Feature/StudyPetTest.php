<?php

namespace Tests\Feature;

use App\Models\Materi;
use App\Models\Subject;
use App\Models\TodoTask;
use App\Models\StudyXpLog;
use App\Services\XpService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudyPetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $user = \App\Models\User::first();
        if ($user) {
            $this->actingAs($user);
        }
    }

    public function test_1_first_daily_login_awards_5_xp(): void
    {
        // Clear seeded logs
        StudyXpLog::truncate();

        $awarded = XpService::awardLoginXp();

        $this->assertTrue($awarded);
        $this->assertDatabaseHas('study_xp_logs', [
            'source' => 'login',
            'xp' => 5,
        ]);
    }

    public function test_2_second_login_same_day_awards_0_xp(): void
    {
        // Clear seeded logs
        StudyXpLog::truncate();

        // 1st login
        XpService::awardLoginXp();
        $this->assertDatabaseCount('study_xp_logs', 1);

        // 2nd login same day
        $awarded = XpService::awardLoginXp();
        $this->assertFalse($awarded);
        $this->assertDatabaseCount('study_xp_logs', 1);
    }

    public function test_3_first_completion_of_todo_awards_10_xp(): void
    {
        $subject = Subject::firstOrCreate(['name' => 'Matematika']);
        $materi = Materi::firstOrCreate(['subject_id' => $subject->id, 'title' => 'Matriks']);
        $task = TodoTask::create([
            'materi_id' => $materi->id,
            'title' => 'Latihan Soal Matriks',
            'status' => 'pending',
        ]);

        $response = $this->patch("/todolist/{$task->id}/complete");

        $response->assertRedirect();
        $this->assertDatabaseHas('study_xp_logs', [
            'source' => 'todo_completed',
            'reference_id' => $task->id,
            'xp' => 10,
        ]);
    }

    public function test_4_and_5_recompleting_same_task_awards_0_additional_xp(): void
    {
        $subject = Subject::first();
        $materi = Materi::firstOrCreate(['title' => 'Matriks Test', 'subject_id' => $subject->id]);
        $task = TodoTask::create([
            'materi_id' => $materi->id,
            'title' => 'Diskusi Soal Matriks',
            'status' => 'pending',
        ]);

        // Complete 1st time
        $this->patch("/todolist/{$task->id}/complete");
        $initialCount = StudyXpLog::count();

        // Toggle completed -> pending
        $this->patch("/todolist/{$task->id}/complete");

        // Toggle pending -> completed 2nd time
        $this->patch("/todolist/{$task->id}/complete");

        // Count should remain unchanged (no duplicate XP farming)
        $this->assertDatabaseCount('study_xp_logs', $initialCount);
    }

    public function test_6_deleting_completed_todo_does_not_subtract_xp(): void
    {
        $subject = Subject::first();
        $materi = Materi::firstOrCreate(['title' => 'Matriks Test 2', 'subject_id' => $subject->id]);
        $task = TodoTask::create([
            'materi_id' => $materi->id,
            'title' => 'Tugas Matriks',
            'status' => 'pending',
        ]);

        $this->patch("/todolist/{$task->id}/complete");
        $initialXp = StudyXpLog::sum('xp');

        // Delete completed task
        $this->delete("/todolist/{$task->id}");

        // Total XP remains unchanged
        $this->assertEquals($initialXp, StudyXpLog::sum('xp'));
    }

    public function test_7_streak_calculation_for_consecutive_active_days(): void
    {
        // Clear seeded logs
        StudyXpLog::truncate();

        $d1 = Carbon::today()->subDays(2)->toDateString();
        $d2 = Carbon::today()->subDays(1)->toDateString();
        $d3 = Carbon::today()->toDateString();

        $userId = auth()->id();
        StudyXpLog::create(['user_id' => $userId, 'source' => 'login', 'activity_date' => $d1, 'xp' => 5]);
        StudyXpLog::create(['user_id' => $userId, 'source' => 'login', 'activity_date' => $d2, 'xp' => 5]);
        StudyXpLog::create(['user_id' => $userId, 'source' => 'login', 'activity_date' => $d3, 'xp' => 5]);

        $this->assertEquals(3, StudyXpLog::getCurrentStreak());
        $this->assertEquals(3, StudyXpLog::getBestStreak());
    }

    public function test_8_streak_resets_on_missing_day_without_dropping_xp_or_level(): void
    {
        // Clear seeded logs
        StudyXpLog::truncate();

        $userId = auth()->id();

        // Active 4 & 3 days ago
        StudyXpLog::create(['user_id' => $userId, 'source' => 'login', 'activity_date' => Carbon::today()->subDays(4)->toDateString(), 'xp' => 5]);
        StudyXpLog::create(['user_id' => $userId, 'source' => 'login', 'activity_date' => Carbon::today()->subDays(3)->toDateString(), 'xp' => 5]);

        // Missed 2 days ago & yesterday

        // Active today
        StudyXpLog::create(['user_id' => $userId, 'source' => 'login', 'activity_date' => Carbon::today()->toDateString(), 'xp' => 5]);

        // Current streak should reset to 1
        $this->assertEquals(1, StudyXpLog::getCurrentStreak());

        // Best streak should remain 2 from peak
        $this->assertEquals(2, StudyXpLog::getBestStreak());

        // Total XP remains intact (15 XP)
        $this->assertEquals(15, StudyXpLog::sum('xp'));
    }

    public function test_9_refreshing_study_pet_page_does_not_award_duplicate_login_xp(): void
    {
        StudyXpLog::truncate();

        // 1st visit
        $this->get('/study-pet');
        $initialCount = StudyXpLog::count(); // 1 (login XP)

        // 2nd visit
        $this->get('/study-pet');
        $this->assertDatabaseCount('study_xp_logs', $initialCount);
    }
}
