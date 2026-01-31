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
            ->setDescriptionForEvent(fn(string $eventName) => $this->generateDescription($eventName));
    }

    /**
     * Generate custom description berdasarkan event dan perubahan field
     */
    protected function generateDescription(string $eventName): string
    {
        $nomorSurat = $this->nomor_telaahan ?? 'Unknown';

        // Handle created event
        if ($eventName === 'created') {
            return "Surat: {$nomorSurat} telah dibuat";
        }

        // Handle deleted event
        if ($eventName === 'deleted') {
            return "Surat: {$nomorSurat} telah dihapus";
        }

        // Handle updated event - check specific changes
        if ($eventName === 'updated') {
            return $this->getUpdateDescription($nomorSurat);
        }

        return "Surat: {$nomorSurat} telah di-{$eventName}";
    }

    /**
     * Generate description untuk update berdasarkan field yang berubah
     */
    protected function getUpdateDescription(string $nomorSurat): string
    {
        $changes = $this->getDirty();

        // Approval Penyetuju Satu (Kabid)
        if (isset($changes['status_penyetuju_satu'])) {
            $status = $changes['status_penyetuju_satu'];
            $penyetuju = $this->penyetujuSatu?->nama_lengkap ?? 'Kabid';

            return match ($status) {
                'disetujui' => "Surat: {$nomorSurat} telah disetujui oleh {$penyetuju} (Kabid)",
                'ditolak' => "Surat: {$nomorSurat} telah ditolak oleh {$penyetuju} (Kabid)",
                'revisi' => "Surat: {$nomorSurat} diminta revisi oleh {$penyetuju} (Kabid)",
                default => "Surat: {$nomorSurat} status Kabid diubah menjadi {$status}",
            };
        }

        // Approval Penyetuju Dua (Kadis)
        if (isset($changes['status_penyetuju_dua'])) {
            $status = $changes['status_penyetuju_dua'];
            $penyetuju = $this->penyetujuDua?->nama_lengkap ?? 'Kadis';

            return match ($status) {
                'disetujui' => "Surat: {$nomorSurat} telah disetujui oleh {$penyetuju} (Kadis)",
                'ditolak' => "Surat: {$nomorSurat} telah ditolak oleh {$penyetuju} (Kadis)",
                'revisi' => "Surat: {$nomorSurat} diminta revisi oleh {$penyetuju} (Kadis)",
                default => "Surat: {$nomorSurat} status Kadis diubah menjadi {$status}",
            };
        }

        // Status keseluruhan berubah
        if (isset($changes['status'])) {
            $status = $changes['status'];

            return match ($status) {
                'diajukan' => "Surat: {$nomorSurat} telah diajukan ulang",
                'disetujui_kabid' => "Surat: {$nomorSurat} telah disetujui oleh Kabid",
                'disetujui_kadis' => "Surat: {$nomorSurat} telah disetujui oleh Kadis dan siap diterbitkan",
                'revisi_kabid' => "Surat: {$nomorSurat} diminta revisi oleh Kabid",
                'revisi_kadis' => "Surat: {$nomorSurat} diminta revisi oleh Kadis",
                'ditolak_kabid' => "Surat: {$nomorSurat} ditolak oleh Kabid",
                'ditolak_kadis' => "Surat: {$nomorSurat} ditolak oleh Kadis",
                default => "Surat: {$nomorSurat} status diubah menjadi {$status}",
            };
        }

        // Nomor surat tugas diterbitkan
        if (isset($changes['nomor_surat_tugas'])) {
            return "Surat Tugas: {$changes['nomor_surat_tugas']} telah diterbitkan untuk {$nomorSurat}";
        }

        // Default untuk perubahan field lainnya
        return "Surat: {$nomorSurat} telah diupdate";
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
