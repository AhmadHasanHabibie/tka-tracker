<?php

namespace Database\Seeders;

use App\Models\TKASubject;
use Illuminate\Database\Seeder;

class TKASubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // Mandatory Subjects (3)
            [
                'name' => 'Bahasa Indonesia',
                'code' => 'bahasa_indonesia',
                'type' => 'mandatory',
            ],
            [
                'name' => 'Matematika',
                'code' => 'matematika',
                'type' => 'mandatory',
            ],
            [
                'name' => 'Bahasa Inggris',
                'code' => 'bahasa_inggris',
                'type' => 'mandatory',
            ],

            // Choice Subjects (20)
            [
                'name' => 'Matematika Tingkat Lanjut',
                'code' => 'matematika_tingkat_lanjut',
                'type' => 'choice',
            ],
            [
                'name' => 'Bahasa Indonesia Tingkat Lanjut',
                'code' => 'bahasa_indonesia_tingkat_lanjut',
                'type' => 'choice',
            ],
            [
                'name' => 'Bahasa Inggris Tingkat Lanjut',
                'code' => 'bahasa_inggris_tingkat_lanjut',
                'type' => 'choice',
            ],
            [
                'name' => 'Fisika',
                'code' => 'fisika',
                'type' => 'choice',
            ],
            [
                'name' => 'Kimia',
                'code' => 'kimia',
                'type' => 'choice',
            ],
            [
                'name' => 'Biologi',
                'code' => 'biologi',
                'type' => 'choice',
            ],
            [
                'name' => 'Pendidikan Pancasila dan Kewarganegaraan',
                'code' => 'pendidikan_pancasila_kewarganegaraan',
                'type' => 'choice',
            ],
            [
                'name' => 'Ekonomi',
                'code' => 'ekonomi',
                'type' => 'choice',
            ],
            [
                'name' => 'Geografi',
                'code' => 'geografi',
                'type' => 'choice',
            ],
            [
                'name' => 'Sosiologi',
                'code' => 'sosiologi',
                'type' => 'choice',
            ],
            [
                'name' => 'Sejarah',
                'code' => 'sejarah',
                'type' => 'choice',
            ],
            [
                'name' => 'Antropologi',
                'code' => 'antropologi',
                'type' => 'choice',
            ],
            [
                'name' => 'Bahasa Prancis',
                'code' => 'bahasa_prancis',
                'type' => 'choice',
            ],
            [
                'name' => 'Bahasa Jerman',
                'code' => 'bahasa_jerman',
                'type' => 'choice',
            ],
            [
                'name' => 'Bahasa Jepang',
                'code' => 'bahasa_jepang',
                'type' => 'choice',
            ],
            [
                'name' => 'Bahasa Mandarin',
                'code' => 'bahasa_mandarin',
                'type' => 'choice',
            ],
            [
                'name' => 'Bahasa Korea',
                'code' => 'bahasa_korea',
                'type' => 'choice',
            ],
            [
                'name' => 'Bahasa Arab',
                'code' => 'bahasa_arab',
                'type' => 'choice',
            ],
            [
                'name' => 'SMK - Produk atau Projek Kreatif dan Kewirausahaan',
                'code' => 'smk_produk_projek_kreatif_kewirausahaan',
                'type' => 'choice',
            ],
            [
                'name' => 'Konsentrasi Keahlian (Khusus SMK)',
                'code' => 'konsentrasi_keahlian_smk',
                'type' => 'choice',
            ],
        ];

        foreach ($subjects as $item) {
            TKASubject::firstOrCreate(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'type' => $item['type'],
                    'is_active' => true,
                ]
            );
        }
    }
}
