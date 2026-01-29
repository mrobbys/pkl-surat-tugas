<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleService
{
  /**
   * Ambil semua data roles untuk datatables
   * 
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getAllRoles()
  {
    // ambil data roles kecuali superadmin
    return Role::with('permissions')
      ->where('name', '!=', 'super-admin')
      // urutkan berdasarkan created_at desc, jika nilai created_at sama maka urutkan berdasarkan id asc
      ->orderByDesc('created_at')
      ->orderBy('id', 'asc')
      ->get();
  }

  /**
   * Ambil data permissions untuk create role
   * 
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getPermissionsList()
  {
    // ambil semua permission, group by 'group', urutkan id asc
    return Permission::orderBy('id', 'asc')
      ->get()
      ->groupBy('group');
  }

  /**
   * Simpan data role
   * 
   * @param array $data
   * 
   * @return \Spatie\Permission\Models\Role
   */
  public function storeRole(array $data)
  {
    // menggunakan db transaction agar proses simpan data ke table roles dan model_has_permissions berhasil
    return DB::transaction(function () use ($data) {
      // buat data role
      $role = Role::create(['name' => $data['name']]);
      if (isset($data['permissions'])) {
        // convert id ke nama permission
        $permissionNames = Permission::whereIn('id', $data['permissions'])
          ->pluck('name')
          ->toArray();
        // sync permissions ke role
        $role->syncPermissions($permissionNames);
      }

      return $role;
    });
  }

  /**
   * Ambil detail data role beserta permissionsnya
   * 
   * @param \Spatie\Permission\Models\Role $role
   * 
   * @return \Spatie\Permission\Models\Role
   */
  public function getRoleDetail(Role $role)
  {
    return $role->load('permissions');
  }

  /**
   * Ambil data role beserta permissionsnya, dan semua permissions yang ada untuk ditampilkan di form edit
   * 
   * @param \Spatie\Permission\Models\Role $role
   * 
   * @return array
   */
  public function getRoleForEdit(Role $role)
  {
    return $role->load('permissions');
  }

  /**
   * Update data role
   * 
   * @param \Spatie\Permission\Models\Role $role
   * @param array $data
   * 
   * @return \Spatie\Permission\Models\Role
   */
  public function updateRole(Role $role, array $data)
  {
    // menggunakan db transaction agar proses update data ke table roles dan model_has_permissions berhasil
    return DB::transaction(function () use ($data, $role) {
      $role->update(['name' => $data['name']]);
      if (isset($data['permissions'])) {
        // convert id ke nama permission
        $permissionNames = Permission::whereIn('id', $data['permissions'])
          ->pluck('name')
          ->toArray();
        // sync permissions ke role
        $role->syncPermissions($permissionNames);
      }

      return $role;
    });
  }

  /**
   * Hapus role
   * 
   * @param \Spatie\Permission\Models\Role $role
   * 
   * @return void
   */
  public function destroyRole(Role $role)
  {
    // cek apakah role masih dipakai oleh user
    $usersCount = $role->users()->count();
    if ($usersCount > 0) {
      throw new \Exception('Role masih digunakan oleh ' . $usersCount . ' user.');
    }

    // hapus role
    $role->delete();
  }
}
