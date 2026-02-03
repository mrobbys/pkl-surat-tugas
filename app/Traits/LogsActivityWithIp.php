<?php

namespace App\Traits;

use Spatie\Activitylog\Traits\LogsActivity;

trait LogsActivityWithIp
{
  use LogsActivity;

  /**
   * Menambahkan IP address dan user agent ke setiap log activity
   */
  public function tapActivity(\Spatie\Activitylog\Contracts\Activity $activity, string $eventName)
  {
    $activity->properties = $activity->properties->merge([
      'ip_address' => request()->ip(),
      'user_agent' => request()->userAgent(),
    ]);
  }
}
