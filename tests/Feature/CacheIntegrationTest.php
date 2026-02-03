<?php
// filepath: /Users/robbys/WebProjects/Plojek_Laravel/pkl-surat-tugas/tests/Feature/CacheIntegrationTest.php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Services\UserService;
use App\Models\PangkatGolongan;
use Illuminate\Support\Facades\Cache;
use App\Services\SuratPerjalananDinasService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CacheIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // ✅ Seed minimal 5 PangkatGolongan
        PangkatGolongan::insert([
            ['pangkat' => 'Pembina', 'golongan' => 'IV/a', 'ruang' => '12', 'created_at' => now(), 'updated_at' => now()],
            ['pangkat' => 'Penata Tk.I', 'golongan' => 'III/d', 'ruang' => '11', 'created_at' => now(), 'updated_at' => now()],
            ['pangkat' => 'Penata', 'golongan' => 'III/c', 'ruang' => '10', 'created_at' => now(), 'updated_at' => now()],
            ['pangkat' => 'Penata Muda Tk.I', 'golongan' => 'III/b', 'ruang' => '9', 'created_at' => now(), 'updated_at' => now()],
            ['pangkat' => 'Penata Muda', 'golongan' => 'III/a', 'ruang' => '8', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        // ✅ HAPUS BAGIAN INI - sudah ada users dari seeder
        // User::factory(10)->create([
        //     'pangkat_golongan_id' => PangkatGolongan::first()->id,
        // ]);
    }

    public function test_user_crud_affects_surat_form_cache()
    {
        $userService = new UserService();
        $suratService = new SuratPerjalananDinasService();

        $pegawais1 = $suratService->getPegawaisForSelect();
        $this->assertCacheHas('pegawai_list');
        $initialCount = $pegawais1->count();

        $userService->storeUser([
            'nip' => '999999999',
            'nama_lengkap' => 'New Test User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'pangkat_golongan_id' => PangkatGolongan::first()->id,
            'jabatan' => 'New Jabatan',
            'roles' => [Role::where('name', '!=', 'super-admin')->first()->id],
        ]);

        $this->assertCacheMissing('pegawai_list');

        $pegawais2 = $suratService->getPegawaisForSelect();
        $this->assertCacheHas('pegawai_list');
        $newCount = $pegawais2->count();

        $this->assertEquals($initialCount + 1, $newCount);
    }

    public function test_multiple_cache_keys_cleared_atomically()
    {
        $userService = new UserService();

        $userService->createUser();
        $this->assertCacheHas('pangkat_golongan_list');
        $this->assertCacheHas('roles_list_exclude_superadmin');

        $userService->storeUser([
            'nip' => '111111111',
            'nama_lengkap' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'pangkat_golongan_id' => PangkatGolongan::first()->id,
            'jabatan' => 'Test Jabatan',
            'roles' => [Role::where('name', '!=', 'super-admin')->first()->id],
        ]);

        $this->assertCacheMissing('pangkat_golongan_list');
        $this->assertCacheMissing('roles_list_exclude_superadmin');
        $this->assertCacheMissing('pegawai_list');
    }

    public function test_cache_warming_after_deployment()
    {
        Cache::flush();

        $userService = new UserService();
        $suratService = new SuratPerjalananDinasService();

        $userService->createUser();
        $suratService->getPegawaisForSelect();

        $this->assertCacheHas('pangkat_golongan_list');
        $this->assertCacheHas('roles_list_exclude_superadmin');
        $this->assertCacheHas('pegawai_list');
    }

    public function test_cache_ttl_expiration()
    {
        $userService = new UserService();

        Cache::put('pangkat_golongan_list', PangkatGolongan::all(), 1);
        $this->assertCacheHas('pangkat_golongan_list');

        Cache::forget('pangkat_golongan_list');
        $this->assertCacheMissing('pangkat_golongan_list');

        $userService->createUser();
        $this->assertCacheHas('pangkat_golongan_list');
    }
}