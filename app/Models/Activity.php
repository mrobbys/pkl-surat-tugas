<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
  /**
   * Ambil alamat IP pengguna dari properti
   */
  public function getIpAddressAttribute(): ?string
  {
    return $this->properties->get('ip_address');
  }

  /**
   * Ambil user agent dari properti
   */
  public function getUserAgentAttribute(): ?string
  {
    return $this->properties->get('user_agent');
  }
}
