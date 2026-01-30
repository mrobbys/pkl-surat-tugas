<?php

namespace App\Models;

use App\Models\PangkatGolongan;
use App\Models\SuratPerjalananDinas;
use App\Traits\LogsActivityWithIp;
use Illuminate\Support\Facades\Crypt;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, CanResetPassword, HasRoles, LogsActivityWithIp;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_lengkap',
        'nip',
        'email',
        'password',
        'pangkat_golongan_id',
        'jabatan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Decrypt nip 
     * 
     * @param string $value
     * 
     * @return string
     */
    public function getNipAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; // fallback jika gagal dekripsi
        }
    }

    /**
     * konfigurasi untuk activity log
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_lengkap', 'email', 'jabatan', 'pangkat_golongan_id'])
            ->logOnlyDirty()
            ->useLogName('user')
            ->setDescriptionForEvent(function (string $eventName) {
                return match ($eventName) {
                    'created' => "User: {$this->nama_lengkap} telah dibuat",
                    'updated' => "User: {$this->nama_lengkap} telah diupdate",
                    'deleted' => "User: {$this->nama_lengkap} telah dihapus",
                    default => "User: {$this->nama_lengkap} telah di-{$eventName}",
                };
            });
    }

    public function pangkatGolongan()
    {
        return $this->belongsTo(PangkatGolongan::class);
    }

    public function suratPerjalananDinasDibuat()
    {
        return $this->hasMany(SuratPerjalananDinas::class, 'pembuat_id');
    }

    public function suratPerjalananDinasDisetujuiSatu()
    {
        return $this->hasMany(SuratPerjalananDinas::class, 'penyetuju_satu_id');
    }

    public function suratPerjalananDinasDisetujuiDua()
    {
        return $this->hasMany(SuratPerjalananDinas::class, 'penyetuju_dua_id');
    }

    public function penugasan()
    {
        return $this->belongsToMany(SuratPerjalananDinas::class, 'penugasan_pegawai', 'user_id', 'surat_id');
    }
}
