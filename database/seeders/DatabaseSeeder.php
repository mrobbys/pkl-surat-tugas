<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SuratPerjalananDinas;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PangkatGolonganSeeder::class,
            RolePermissionSeeder::class,
        ]);

        // Buat 20 user dengan role 'user'
        User::factory(20)->create()->each(function ($user) {
            $user->assignRole('user');
        });

        // Buat 5 user dengan role 'kasi'
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('kasi');
        });
        
        // Buat 3 user dengan role 'kabid'
        User::factory(2)->create()->each(function ($user) {
            $user->assignRole('kabid');
        });

        // Ambil semua user yang bisa ditugaskan (kecuali super-admin)
        $usersForAssignment = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->pluck('id')->toArray();

        // Buat surat dengan berbagai status
        // 4 Surat - Status Diajukan (belum ada approval)
        SuratPerjalananDinas::factory(4)->create()->each(function ($surat) use ($usersForAssignment) {
            // Attach random pegawai (1-7 orang)
            $jumlahPegawai = rand(1, 7);
            $pegawaiIds = collect($usersForAssignment)->random(min($jumlahPegawai, count($usersForAssignment)));
            $surat->pegawaiDitugaskan()->attach($pegawaiIds);
        });

        // 3 Surat - Status Disetujui Kabid (menunggu approval Kadis)
        SuratPerjalananDinas::factory(3)->disetujuiKabid()->create()->each(function ($surat) use ($usersForAssignment) {
            $jumlahPegawai = rand(2, 6);
            $pegawaiIds = collect($usersForAssignment)->random(min($jumlahPegawai, count($usersForAssignment)));
            $surat->pegawaiDitugaskan()->attach($pegawaiIds);
        });

        // 7 Surat - Status Disetujui Kadis
        SuratPerjalananDinas::factory(7)->disetujuiKadis()->create()->each(function ($surat) use ($usersForAssignment) {
            $jumlahPegawai = rand(3, 8);
            $pegawaiIds = collect($usersForAssignment)->random(min($jumlahPegawai, count($usersForAssignment)));
            $surat->pegawaiDitugaskan()->attach($pegawaiIds);
        });

        // 4 Surat - Status Revisi Kabid
        SuratPerjalananDinas::factory(4)->revisiKabid()->create()->each(function ($surat) use ($usersForAssignment) {
            $jumlahPegawai = rand(1, 4);
            $pegawaiIds = collect($usersForAssignment)->random(min($jumlahPegawai, count($usersForAssignment)));
            $surat->pegawaiDitugaskan()->attach($pegawaiIds);
        });

        // 6 Surat - Status Revisi Kadis
        SuratPerjalananDinas::factory(6)->revisiKadis()->create()->each(function ($surat) use ($usersForAssignment) {
            $jumlahPegawai = rand(2, 5);
            $pegawaiIds = collect($usersForAssignment)->random(min($jumlahPegawai, count($usersForAssignment)));
            $surat->pegawaiDitugaskan()->attach($pegawaiIds);
        });

        // 9 Surat - Status Ditolak Kadis
        SuratPerjalananDinas::factory(9)->ditolakKadis()->create()->each(function ($surat) use ($usersForAssignment) {
            $jumlahPegawai = rand(2, 4);
            $pegawaiIds = collect($usersForAssignment)->random(min($jumlahPegawai, count($usersForAssignment)));
            $surat->pegawaiDitugaskan()->attach($pegawaiIds);
        });

        // 3 Surat - Status Ditolak Kabid
        SuratPerjalananDinas::factory(3)->ditolakKabid()->create()->each(function ($surat) use ($usersForAssignment) {
            $jumlahPegawai = rand(1, 3);
            $pegawaiIds = collect($usersForAssignment)->random(min($jumlahPegawai, count($usersForAssignment)));
            $surat->pegawaiDitugaskan()->attach($pegawaiIds);
        });
    }
}
