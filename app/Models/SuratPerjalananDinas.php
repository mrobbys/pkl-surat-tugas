<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityWithIp;
use Spatie\Activitylog\LogOptions;

use Carbon\Carbon;

class SuratPerjalananDinas extends Model
{
    use LogsActivityWithIp;

    protected $guarded = [];

    /**
     * konfigurasi untuk activity log
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nomor_telaahan',
                'nomor_surat_tugas',
                'nomor_nota_dinas',
                'kepada_yth',
                'perihal_kegiatan',
                'tempat_pelaksanaan',
                'tanggal_mulai',
                'tanggal_selesai',
                'status',
                'status_penyetuju_satu',
                'catatan_satu',
                'status_penyetuju_dua',
                'catatan_dua',
                'penyetuju_satu_id',
                'penyetuju_dua_id'
            ])
            ->logOnlyDirty()
            ->useLogName('surat_perjalanan_dinas')
            ->setDescriptionForEvent(function (string $eventName) {
                return match ($eventName) {
                    'created' => "Surat: {$this->nomor_telaahan} telah dibuat",
                    'updated' => "Surat: {$this->nomor_telaahan} telah diupdate",
                    'deleted' => "Surat: {$this->nomor_telaahan} telah dihapus",
                    default => "Surat: {$this->nomor_telaahan} telah di-{$eventName}",
                };
            });
    }

    public function penyetujuSatu()
    {
        return $this->belongsTo(User::class, 'penyetuju_satu_id');
    }

    public function penyetujuDua()
    {
        return $this->belongsTo(User::class, 'penyetuju_dua_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'pembuat_id');
    }

    public function pegawaiDitugaskan()
    {
        return $this->belongsToMany(User::class, 'penugasan_pegawai', 'surat_id', 'user_id');
    }
}
