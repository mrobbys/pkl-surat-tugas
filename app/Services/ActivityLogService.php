<?php

namespace App\Services;

use App\Models\Activity;

class ActivityLogService
{
  public function getAllLogs()
  {
    return Activity::select(
      'id',
      'log_name',
      'description',
      'causer_type',
      'causer_id',
      'properties',
      'created_at'
    )
      ->with('causer:id,email')
      ->orderByDesc('created_at')
      ->get();
  }
}
