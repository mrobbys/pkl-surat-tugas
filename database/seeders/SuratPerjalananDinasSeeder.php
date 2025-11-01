<?php

namespace Database\Seeders;

use App\Models\SuratPerjalananDinas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuratPerjalananDinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SuratPerjalananDinas::create([
            'kepada_yth' => 'Kepala Dinas',
            'dari' => 'Sekretaris Dinas',
            'nomor_telaahan' => '123/ABC/2023',
            'tanggal_telaahan' => now(),
            'perihal_kegiatan' => 'Kegiatan contoh',
            'tempat_pelaksanaan' => 'Jakarta',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(3),
            'dasar_telaahan' => 'Dasar telaahan contoh',
            'isi_telaahan' => ' <ol>
                                	<li>satu</li>
                                	<li>dua&nbsp;</li>
                                	<li>tiga</li>
                                </ol>',
            'pembuat_id' => 1,
        ]);
    }
}
