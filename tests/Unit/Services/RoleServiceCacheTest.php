<?php
// filepath: /Users/robbys/WebProjects/Plojek_Laravel/pkl-surat-tugas/tests/Unit/Services/RoleServiceCacheTest.php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Role;
use App\Services\RoleService;
use App\Models\PangkatGolongan;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleServiceCacheTest extends TestCase
{
    use RefreshDatabase;

    private RoleService $roleService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roleService = new RoleService();

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

    public function test_permissions_cache_is_created_on_first_call()
    {
        Cache::flush();
        $permissions = $this->roleService->getPermissionsList();

        $this->assertCacheHas('permissions_grouped');
        $this->assertNotEmpty($permissions);
    }

    public function test_cache_is_cleared_after_role_created()
    {
        $this->roleService->getPermissionsList();
        $this->assertCacheHas('permissions_grouped');

        $this->roleService->storeRole([
            'name' => 'test-role',
            'permissions' => Permission::take(2)->pluck('id')->toArray(),
        ]);

        $this->assertCacheMissing('permissions_grouped');
    }

    public function test_cache_is_cleared_after_role_updated()
    {
        $role = Role::where('name', '!=', 'super-admin')->first();

        $this->roleService->getPermissionsList();
        $this->assertCacheHas('permissions_grouped');

        $this->roleService->updateRole($role, [
            'name' => 'updated-role-name',
            'permissions' => Permission::take(3)->pluck('id')->toArray(),
        ]);

        $this->assertCacheMissing('permissions_grouped');
    }

    public function test_cache_is_cleared_after_role_deleted()
    {
        $role = Role::where('name', '!=', 'super-admin')->first();

        $this->roleService->getPermissionsList();
        $this->assertCacheHas('permissions_grouped');

        $this->roleService->destroyRole($role);

        $this->assertCacheMissing('permissions_grouped');
    }
}