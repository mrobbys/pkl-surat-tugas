<?php
// filepath: /Users/robbys/WebProjects/Plojek_Laravel/pkl-surat-tugas/tests/Unit/Services/UserServiceCacheTest.php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use App\Models\PangkatGolongan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserServiceCacheTest extends TestCase
{
    use RefreshDatabase;

    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();

        // ✅ Seed minimal 5 PangkatGolongan untuk support seeder
        PangkatGolongan::insert([
            ['pangkat' => 'Pembina', 'golongan' => 'IV/a', 'ruang' => '12', 'created_at' => now(), 'updated_at' => now()],
            ['pangkat' => 'Penata Tk.I', 'golongan' => 'III/d', 'ruang' => '11', 'created_at' => now(), 'updated_at' => now()],
            ['pangkat' => 'Penata', 'golongan' => 'III/c', 'ruang' => '10', 'created_at' => now(), 'updated_at' => now()],
            ['pangkat' => 'Penata Muda Tk.I', 'golongan' => 'III/b', 'ruang' => '9', 'created_at' => now(), 'updated_at' => now()],
            ['pangkat' => 'Penata Muda', 'golongan' => 'III/a', 'ruang' => '8', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_cache_is_created_on_first_call()
    {
        Cache::flush();
        $data = $this->userService->createUser();

        $this->assertCacheHas('pangkat_golongan_list');
        $this->assertCacheHas('roles_list_exclude_superadmin');
        $this->assertNotEmpty($data['pangkat_golongan']);
        $this->assertNotEmpty($data['all_roles']);
    }

    public function test_cache_is_hit_on_second_call()
    {
        $data1 = $this->userService->createUser();

        DB::enableQueryLog();
        $data2 = $this->userService->createUser();
        $queryLog = DBnv::getQueryLog();

        $this->assertCount(0, $queryLog);
        $this->assertEquals($data1['pangkat_golongan']->count(), $data2['pangkat_golongan']->count());
    }

    public function test_cache_is_cleared_after_user_created()
    {
        $this->userService->createUser();
        $this->assertCacheHas('pangkat_golongan_list');

        $this->userService->storeUser([
            'nip' => '123456789',
            'nama_lengkap' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'pangkat_golongan_id' => PangkatGolongan::first()->id,
            'jabatan' => 'Test Jabatan',
            'roles' => [Role::where('name', '!=', 'super-admin')->first()->id],
        ]);

        $this->assertCacheMissing('pangkat_golongan_list');
    }

    public function test_cache_is_cleared_after_user_updated()
    {
        $user = User::factory()->create([
            'pangkat_golongan_id' => PangkatGolongan::first()->id,
        ]);

        $this->userService->createUser();
        $this->assertCacheHas('pangkat_golongan_list');

        $this->userService->updateUser($user, [
            'nip' => '987654321',
            'nama_lengkap' => 'Updated User',
            'email' => 'updated@example.com',
            'pangkat_golongan_id' => PangkatGolongan::first()->id,
            'jabatan' => 'Updated Jabatan',
        ]);

        $this->assertCacheMissing('pangkat_golongan_list');
    }

    public function test_cache_is_cleared_after_user_deleted()
    {
        // ✅ Create authenticated user (super-admin)
        $authUser = User::factory()->create([
            'pangkat_golongan_id' => PangkatGolongan::first()->id,
            'email' => 'admin@test.com',
        ]);
        $authUser->assignRole('super-admin');

        // ✅ Create user yang akan dihapus
        $user = User::factory()->create([
            'pangkat_golongan_id' => PangkatGolongan::first()->id,
            'email' => 'todelete@test.com',
        ]);
        $user->assignRole(Role::where('name', '!=', 'super-admin')->first());

        $this->userService->createUser();
        $this->assertCacheHas('pangkat_golongan_list');

        // ✅ Authenticate sebagai super-admin sebelum delete
        $this->actingAs($authUser);

        $this->userService->deleteUser($user);

        $this->assertCacheMissing('pangkat_golongan_list');
    }

    public function test_cache_data_is_consistent_with_database()
    {
        $cachedData = $this->userService->createUser();

        $freshPangkatGolongan = PangkatGolongan::orderBy('id', 'asc')->get();
        $freshRoles = Role::where('name', '!=', 'super-admin')->orderBy('id', 'asc')->get();

        $this->assertEquals($freshPangkatGolongan->count(), $cachedData['pangkat_golongan']->count());
        $this->assertEquals($freshRoles->count(), $cachedData['all_roles']->count());
    }
}