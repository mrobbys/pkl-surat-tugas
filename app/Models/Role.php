<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use App\Traits\LogsActivityWithIp;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
  use LogsActivityWithIp;

  /**
   * konfigurasi activity log untuk Role
   */
  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logOnly(['name', 'guard_name'])
      ->logOnlyDirty()
      ->useLogName('role')
      ->setDescriptionForEvent(fn(string $eventName) => "Role: {$this->name} telah di-{$eventName}");
  }

  /**
   * log untuk permissions
   */
  public function syncPermissions(...$permissions)
  {
    // ambil permission lama sebelum sync
    $oldPermissions = $this->permissions()->pluck('name')->toArray();

    // panggil method parent
    $result = parent::syncPermissions(...$permissions);

    // ambil permission baru setelah sync
    $newPermissions = $this->permissions()->pluck('name')->toArray();

    // log perubahan permissions jika ada perbedaan
    if ($oldPermissions !== $newPermissions) {
      activity('role')
        ->performedOn($this)
        ->causedBy(Auth::user() ?? null)
        ->withProperties([
          'ip_address' => request()->ip(),
          'user_agent' => request()->userAgent(),
          'old_permissions' => $oldPermissions,
          'new_permissions' => $newPermissions,
        ])
        ->log("Permissions untuk role: {$this->name} telah diubah");
    }

    return $result;
  }
}
