<?php

namespace Database\Seeders;

use App\Models\Materi;
use App\Models\Subject;
use App\Models\TodoTask;
use App\Models\User;
use Illuminate\Database\Seeder;

class MateriSeeder extends Seeder
{
    public function run(): void
    {
        $mat = Subject::where('name', 'TKA Matematika')->first();
        $ind = Subject::where('name', 'TKA Bahasa Indonesia')->first();
        $ing = Subject::where('name', 'TKA Bahasa Inggris')->first();

        $users = User::all();

        foreach ($users as $user) {
            if ($mat) {
                $m1 = Materi::firstOrCreate(['user_id' => $user->id, 'title' => 'Persamaan Linear'], [
                    'subject_id' => $mat->id,
                    'description' => 'Sistem persamaan linear 2 & 3 variabel.',
                ]);

                TodoTask::firstOrCreate(['user_id' => $user->id, 'title' => 'Pahami Metode Eliminasi & Subtitusi', 'materi_id' => $m1->id], [
                    'status' => 'completed',
                ]);
                TodoTask::firstOrCreate(['user_id' => $user->id, 'title' => 'Kerjakan 15 Soal Latihan UTBK', 'materi_id' => $m1->id], [
                    'status' => 'pending',
                    'due_date' => now()->addDays(2),
                ]);

                $m2 = Materi::firstOrCreate(['user_id' => $user->id, 'title' => 'Statistika & Peluang'], [
                    'subject_id' => $mat->id,
                    'description' => 'Mean, Median, Modus, Kuartil, dan Permutasi.',
                ]);

                TodoTask::firstOrCreate(['user_id' => $user->id, 'title' => 'Rangkum Rumus Statistika Data Kelompok', 'materi_id' => $m2->id], [
                    'status' => 'pending',
                ]);
            }

            if ($ind) {
                $m3 = Materi::firstOrCreate(['user_id' => $user->id, 'title' => 'Ide Pokok & Kalimat Utama'], [
                    'subject_id' => $ind->id,
                    'description' => 'Menganalisis paragraf deduktif dan induktif.',
                ]);

                TodoTask::firstOrCreate(['user_id' => $user->id, 'title' => 'Membaca 5 Teks Artikel Latihan', 'materi_id' => $m3->id], [
                    'status' => 'completed',
                ]);
            }

            if ($ing) {
                $m4 = Materi::firstOrCreate(['user_id' => $user->id, 'title' => 'Simple Past & Present Perfect Tense'], [
                    'subject_id' => $ing->id,
                    'description' => 'Perbedaan penggunaan waktu dan kata kerja.',
                ]);

                TodoTask::firstOrCreate(['user_id' => $user->id, 'title' => 'Pelajari Irregular Verbs List', 'materi_id' => $m4->id], [
                    'status' => 'pending',
                ]);
            }
        }
    }
}
