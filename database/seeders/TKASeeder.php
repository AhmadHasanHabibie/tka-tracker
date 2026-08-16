<?php

namespace Database\Seeders;

use App\Models\TKATryout;
use App\Models\TKASubject;
use App\Models\TKASubjectScore;
use App\Models\User;
use Illuminate\Database\Seeder;

class TKASeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $to1 = TKATryout::firstOrCreate(
                ['user_id' => $user->id, 'name' => 'TKA Tryout #1'],
                [
                    'date' => '2026-08-16',
                    'notes' => 'Tryout TKA pertama (Siswa SMK).',
                ]
            );

            $to1Mandatory = [
                'bahasa_indonesia' => 78.50,
                'matematika' => 65.25,
                'bahasa_inggris' => 72.00,
            ];

            foreach ($to1Mandatory as $code => $score) {
                $subject = TKASubject::where('code', $code)->first();
                if ($subject) {
                    TKASubjectScore::firstOrCreate([
                        'tka_tryout_id' => $to1->id,
                        'tka_subject_id' => $subject->id,
                    ], [
                        'subject_name' => $subject->name,
                        'subject_type' => 'mandatory',
                        'score' => $score,
                    ]);
                }
            }

            $to1Choice = [
                'smk_produk_projek_kreatif_kewirausahaan' => 82.50,
                'konsentrasi_keahlian_smk' => 87.00,
            ];

            foreach ($to1Choice as $code => $score) {
                $subject = TKASubject::where('code', $code)->first();
                if ($subject) {
                    TKASubjectScore::firstOrCreate([
                        'tka_tryout_id' => $to1->id,
                        'tka_subject_id' => $subject->id,
                    ], [
                        'subject_name' => $subject->name,
                        'subject_type' => 'choice',
                        'score' => $score,
                    ]);
                }
            }

            $to2 = TKATryout::firstOrCreate(
                ['user_id' => $user->id, 'name' => 'TKA Tryout #2'],
                [
                    'date' => '2026-08-20',
                    'notes' => 'Peningkatan nilai Matematika dan Konsentrasi Keahlian.',
                ]
            );

            $to2Mandatory = [
                'bahasa_indonesia' => 82.00,
                'matematika' => 71.50,
                'bahasa_inggris' => 76.00,
            ];

            foreach ($to2Mandatory as $code => $score) {
                $subject = TKASubject::where('code', $code)->first();
                if ($subject) {
                    TKASubjectScore::firstOrCreate([
                        'tka_tryout_id' => $to2->id,
                        'tka_subject_id' => $subject->id,
                    ], [
                        'subject_name' => $subject->name,
                        'subject_type' => 'mandatory',
                        'score' => $score,
                    ]);
                }
            }

            $to2Choice = [
                'smk_produk_projek_kreatif_kewirausahaan' => 85.00,
                'konsentrasi_keahlian_smk' => 90.00,
            ];

            foreach ($to2Choice as $code => $score) {
                $subject = TKASubject::where('code', $code)->first();
                if ($subject) {
                    TKASubjectScore::firstOrCreate([
                        'tka_tryout_id' => $to2->id,
                        'tka_subject_id' => $subject->id,
                    ], [
                        'subject_name' => $subject->name,
                        'subject_type' => 'choice',
                        'score' => $score,
                    ]);
                }
            }
        }
    }
}
