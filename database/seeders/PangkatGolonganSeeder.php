<?php

namespace Database\Seeders;

use App\Models\PangkatGolongan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PangkatGolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $pangkatGolongans = [
            ['pangkat' => 'Juru Muda', 'golongan' => 'I', 'ruang' => 'a'],
            ['pangkat' => 'Juru Muda Tingkat I', 'golongan' => 'I', 'ruang' => 'b'],
            ['pangkat' => 'Juru', 'golongan' => 'I', 'ruang' => 'c'],
            ['pangkat' => 'Juru Tingkat I', 'golongan' => 'I', 'ruang' => 'd'],

            ['pangkat' => 'Pengatur Muda', 'golongan' => 'II', 'ruang' => 'a'],
            ['pangkat' => 'Pengatur Muda Tingkat I', 'golongan' => 'II', 'ruang' => 'b'],
            ['pangkat' => 'Pengatur Muda', 'golongan' => 'II', 'ruang' => 'c'],
            ['pangkat' => 'Pengatur Muda', 'golongan' => 'II', 'ruang' => 'd'],

            ['pangkat' => 'Penata Muda', 'golongan' => 'III', 'ruang' => 'a'],
            ['pangkat' => 'Penata Muda Tingkat I', 'golongan' => 'III', 'ruang' => 'b'],
            ['pangkat' => 'Penata', 'golongan' => 'III', 'ruang' => 'c'],
            ['pangkat' => 'Penata Tingkat I', 'golongan' => 'III', 'ruang' => 'd'],

            ['pangkat' => 'Pembina', 'golongan' => 'IV', 'ruang' => 'a'],
            ['pangkat' => 'Pembina Tingkat I',  'golongan' => 'IV', 'ruang' => 'b'],
            ['pangkat' => 'Pembina Utama Muda',  'golongan' => 'IV', 'ruang' => 'c'],
            ['pangkat' => 'Pembina Utama Madya',  'golongan' => 'IV', 'ruang' => 'd'],
            ['pangkat' => 'Pembina Utama',  'golongan' => 'IV', 'ruang' => 'e'],
        ];

        foreach ($pangkatGolongans as $pg) {
            PangkatGolongan::create($pg);
        }
    }
}
