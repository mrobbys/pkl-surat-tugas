<?php
// filepath: /Users/robbys/WebProjects/Plojek_Laravel/pkl-surat-tugas/tests/Unit/Services/SuratPerjalananDinasServiceCacheTest.php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\PangkatGolongan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\SuratPerjalananDinasService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuratPerjalananDinasServiceCacheTest extends TestCase
{
    use RefreshDatabase;

    private SuratPerjalananDinasService $suratService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->suratService = new SuratPerjalananDinasService();

        // ✅ Seed minimal 5 PangkatGolongan
        PangkatGolongan::insert([
            ['pangkat' => 'Pembina', 'golongan' => 'IV/a', 'ruang' => '12', 'created_at' => now(), 'updated_at' => now()],
            ['pangkat' => 'Penata Tk.I', 'golongan' => 'III/d', 'ruang' => '11', 'created_at' => now(), 'updated_at' => now()],
            ['pangkat' => 'Penata', 'golongan' => 'III/c', 'ruang' => '10', 'created_at' => now(), 'updated_at' => now()],
            ['pangkat' => 'Penata Muda Tk.I', 'golongan' => 'III/b', 'ruang' => '9', 'created_at' => now(), 'updated_at' => now()],
            ['pangkat' => 'Penata Muda', 'golongan' => 'III/a', 'ruang' => '8', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        User::factory(5)->create([
            'pangkat_golongan_id' => PangkatGolongan::first()->id,
        ]);
    }

    public function test_pegawai_cache_is_created_on_first_call()
    {
        Cache::flush();
        $pegawais = $this->suratService->getPegawaisForSelect();

        $this->assertCacheHas('pegawai_list');
        $this->assertNotEmpty($pegawais);
        $this->assertGreaterThanOrEqual(5, $pegawais->count());
    }

    public function test_pegawai_cache_is_hit_on_second_call()
    {
        $data1 = $this->suratService->getPegawaisForSelect();

        DB::enableQueryLog();
        $data2 = $this->suratService->getPegawaisForSelect();
        $queryLog = DB::getQueryLog();

        $this->assertCount(0, $queryLog);
        $this->assertEquals($data1->count(), $data2->count());
    }

    public function test_pegawai_cache_is_not_cleared_after_surat_created()
    {
        $this->actingAs(User::first());

        $this->suratService->getPegawaisForSelect();
        $this->assertCacheHas('pegawai_list');

        $this->suratService->storeTelaahStaf([
            'kepada_yth' => 'Kepala Dinas',
            'dari' => 'Kabid',
            'nomor_telaahan' => 'TL-001/2024',
            'tanggal_telaahan' => now(),
            'perihal_kegiatan' => 'Test Kegiatan',
            'tempat_pelaksanaan' => 'Jakarta',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(3),
            'dasar_telaahan' => 'Test Dasar',
            'isi_telaahan' => 'Test Isi',
            'pegawais' => User::take(2)->pluck('id')->toArray(),
        ]);

        $this->assertCacheHas('pegawai_list');
    }

    public function test_pegawai_cache_is_cleared_after_user_crud()
    {
        $this->suratService->getPegawaisForSelect();
        $this->assertCacheHas('pegawai_list');

        User::factory()->create([
            'pangkat_golongan_id' => PangkatGolongan::first()->id,
        ]);

        $this->assertCacheMissing('pegawai_list');
    }
}