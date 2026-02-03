<?php
// filepath: /Users/robbys/WebProjects/Plojek_Laravel/pkl-surat-tugas/tests/Feature/ModelCacheEventsTest.php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\PangkatGolongan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelCacheEventsTest extends TestCase
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
    }

    public function test_user_model_clears_cache_on_create()
    {
        Cache::put('pangkat_golongan_list', 'test');
        Cache::put('roles_list_exclude_superadmin', 'test');
        Cache::put('pegawai_list', 'test');

        User::factory()->create([
            'pangkat_golongan_id' => PangkatGolongan::first()->id,
        ]);

        $this->assertCacheMissing('pangkat_golongan_list');
        $this->assertCacheMissing('roles_list_exclude_superadmin');
        $this->assertCacheMissing('pegawai_list');
    }

    public function test_user_model_clears_cache_on_update()
    {
        $user = User::factory()->create([
            'pangkat_golongan_id' => PangkatGolongan::first()->id,
        ]);

        Cache::put('pangkat_golongan_list', 'test');
        Cache::put('pegawai_list', 'test');

        $user->update(['nama_lengkap' => 'Updated Name']);

        $this->assertCacheMissing('pangkat_golongan_list');
        $this->assertCacheMissing('pegawai_list');
    }

    public function test_user_model_clears_cache_on_delete()
    {
        $user = User::factory()->create([
            'pangkat_golongan_id' => PangkatGolongan::first()->id,
        ]);

        Cache::put('pegawai_list', 'test');
        $user->delete();

        $this->assertCacheMissing('pegawai_list');
    }

    public function test_pangkat_golongan_model_clears_cache_on_create()
    {
        Cache::put('pangkat_golongan_list', 'test');

        PangkatGolongan::create([
            'pangkat' => 'Test Pangkat',
            'golongan' => 'IV/e',
            'ruang' => '15'
        ]);

        $this->assertCacheMissing('pangkat_golongan_list');
    }

    public function test_role_model_clears_cache_on_create()
    {
        Cache::put('roles_list_exclude_superadmin', 'test');
        Cache::put('permissions_grouped', 'test');

        Role::create(['name' => 'test-role', 'guard_name' => 'web']);

        $this->assertCacheMissing('roles_list_exclude_superadmin');
        $this->assertCacheMissing('permissions_grouped');
    }

    public function test_multiple_models_clear_different_cache_keys()
    {
        Cache::put('pangkat_golongan_list', 'test1');
        Cache::put('roles_list_exclude_superadmin', 'test2');
        Cache::put('pegawai_list', 'test3');
        Cache::put('permissions_grouped', 'test4');

        User::factory()->create([
            'pangkat_golongan_id' => PangkatGolongan::first()->id,
        ]);

        $this->assertCacheMissing('pangkat_golongan_list');
        $this->assertCacheMissing('roles_list_exclude_superadmin');
        $this->assertCacheMissing('pegawai_list');
        $this->assertCacheHas('permissions_grouped');

        Cache::put('roles_list_exclude_superadmin', 'test2');
        Cache::put('permissions_grouped', 'test4');

        Role::create(['name' => 'another-test-role', 'guard_name' => 'web']);

        $this->assertCacheMissing('roles_list_exclude_superadmin');
        $this->assertCacheMissing('permissions_grouped');
    }
}