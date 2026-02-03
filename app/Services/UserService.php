<?php

namespace App\Services;

use App\Models\User;
use App\Models\PangkatGolongan;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class UserService
{
  /**
   * Ambil semua data users untuk datatables
   * 
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getAllUsers()
  {
    // ambil data users dengan relasi pangkatGolongan dan roles, kecuali superadmin
    return User::with(['pangkatGolongan', 'roles'])
      ->whereDoesntHave('roles', function ($q) {
        $q->where('name', 'super-admin');
      })
      // urutkan berdasarkan created_at desc
      ->orderByDesc('created_at')
      ->get();
  }

  /**
   * Ambil data pangkat golongan dan roles untuk kecuali role superadmin
   * 
   * @return array
   */
  public function createUser()
  {
    return [
      'pangkat_golongan' => Cache::remember('pangkat_golongan_list', 86400, function () {
        return PangkatGolongan::orderBy('id', 'asc')->get();
      }),

      'all_roles' => Cache::remember('roles_list_exclude_superadmin', 86400, function () {
        return Role::where('name', '!=', 'super-admin')->orderBy('id', 'asc')->get();
      }),
    ];
  }

  /**
   * Simpan data user
   * 
   * @param array $data
   * 
   * @return \App\Models\User
   */
  public function storeUser(array $data)
  {
    // menggunakan db transaction agar proses simpan data ke table user dan assign role ke user berhasil
    return DB::transaction(function () use ($data) {
      // buat data user
      $user = User::create([
        // enkripsi nip sebelum disimpan ke database
        'nip' => Crypt::encryptString($data['nip']),
        'nama_lengkap' => trim($data['nama_lengkap']),
        'email' => strtolower(trim($data['email'])),
        'password' => Hash::make($data['password']),
        'pangkat_golongan_id' => $data['pangkat_golongan_id'],
        'jabatan' => trim($data['jabatan']),
      ]);

      // jika roles ada dan tidak kosong, maka assign role ke user
      if (isset($data['roles']) && !empty($data['roles'])) {
        $rolesNames = Role::whereIn('id', $data['roles'])
          ->pluck('name')
          ->toArray();
        $user->assignRole($rolesNames);
      }
      return $user->fresh(['pangkatGolongan', 'roles']);
    });
  }

  /**
   * Ambil data user beserta relasi pangkatGolongan dan roles
   * 
   * @param \App\Models\User $user
   * 
   * @return \App\Models\User
   */
  public function getUser(User $user)
  {
    return $user->load('pangkatGolongan', 'roles');
  }

  /**
   * Update data user
   * 
   * @param \App\Models\User $user
   * @param array $data
   * 
   * @return \App\Models\User
   */
  public function updateUser(User $user, array $data)
  {
    // menggunakan db transaction agar proses update data ke table user dan sync role ke user berhasil
    return DB::transaction(function () use ($user, $data) {
      // update data user
      $user->update([
        'nama_lengkap' => trim($data['nama_lengkap']),
        'email' => strtolower(trim($data['email'])),
        'pangkat_golongan_id' => $data['pangkat_golongan_id'],
        'jabatan' => trim($data['jabatan']),
      ]);

      // jika nip berbeda, maka update nip user
      if ($user->nip !== $data['nip']) {
        $user->update(['nip' => Crypt::encryptString($data['nip'])]);
      }

      // jika password ada dan tidak kosong, maka update password user
      if (isset($data['password']) && !empty($data['password'])) {
        $user->update(['password' => Hash::make($data['password'])]);
      }
      // jika roles ada dan tidak kosong, maka sync role ke user
      if (isset($data['roles']) && !empty($data['roles'])) {
        $rolesNames = Role::whereIn('id', $data['roles'])
          ->pluck('name')
          ->toArray();
        $user->syncRoles($rolesNames);
      }

      

      return $user->fresh(['pangkatGolongan', 'roles']);
    });
  }

  /**
   * Hapus data user
   * 
   * @param \App\Models\User $user
   * 
   * @return bool
   */
  public function deleteUser(User $user)
  {
    // jangan hapus akun sendiri
    if ($user->id === Auth::user()->id) {
      throw new \Exception("Anda tidak dapat menghapus akun sendiri.");
    }

    // cek apakah user adalah super-admin
    if ($user->hasRole('super-admin')) {
      throw new \Exception("Anda tidak dapat menghapus akun dengan role super-admin.");
    }

    DB::transaction(function () use ($user) {
      // hapus semua role dari user
      $user->syncRoles([]);
      // hapus user
      $user->delete();
      
    });
  }
}
