<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, CanResetPassword, HasRoles;

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
