<?php

namespace App\Models;

use App\Traits\LogsActivityWithIp;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class PangkatGolongan extends Model
{
    use LogsActivityWithIp;

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * konfigurasi untuk activity log
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['pangkat', 'golongan', 'ruang'])
            ->logOnlyDirty()
            ->useLogName('pangkat_golongan')
            ->setDescriptionForEvent(function (string $eventName) {
                return match ($eventName) {
                    'created' => "Pangkat Golongan: {$this->pangkat} {$this->golongan}/{$this->ruang} telah dibuat",
                    'updated' => "Pangkat Golongan: {$this->pangkat} {$this->golongan}/{$this->ruang} telah diupdate",
                    'deleted' => "Pangkat Golongan: {$this->pangkat} {$this->golongan}/{$this->ruang} telah dihapus",
                    default => "Pangkat Golongan: {$this->pangkat} {$this->golongan}/{$this->ruang} telah di-{$eventName}",
                };
            });
    }
}
