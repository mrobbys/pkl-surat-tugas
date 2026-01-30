<?php

namespace App\Services;

use App\Models\Activity;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\SuratPerjalananDinas;

class DashboardService
{
  /**
   * Data statistik untuk dashboard
   * @return array
   */
  public function getDashboardStats()
  {
    $currentYear = (int) date('Y');

    return [
      'totalPegawai' => $this->getTotalPegawai(),
      'totalRoles' => Role::count(),
      'totalSurat' => $this->getTotalSurat($currentYear),
      'recentSurat' => $this->getRecentSurat(),
      'recentActivities' => $this->getRecentActivities(),
      'currentYear' => $currentYear,
    ];
  }

  public function getTotalSurat(int $year)
  {
    return SuratPerjalananDinas::whereYear('created_at', $year)->count();
  }

  /**
   * Total pegawai kecuali super-admin
   * @return int
   */
  public function getTotalPegawai()
  {
    return User::whereDoesntHave('roles', function ($query) {
      $query->where('name', 'super-admin');
    })->count();
  }

  /**
   * 5 surat perjalanan dinas terakhir yang baru dibuat
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getRecentSurat()
  {
    return SuratPerjalananDinas::select('id', 'nomor_telaahan', 'tanggal_telaahan', 'status', 'pembuat_id')
      ->with('pembuat:id,nama_lengkap')
      ->latest()
      ->take(5)
      ->get();
  }

  public function getRecentActivities()
  {
  return Activity::select('id', 'causer_id', 'causer_type', 'description', 'log_name')
      ->with('causer:id,email')
      ->latest()
      ->take(5)
      ->get();
  }

  /**
   * Statistik status pengajuan surat perjalanan dinas
   * @return array
   */
  public function getStatusStatistics()
  {
    $year = (int) date('Y');

    $rawStatus = SuratPerjalananDinas::query()
      ->select('status', DB::raw('count(*) as total'))
      ->whereYear('created_at', $year)
      ->groupBy('status')
      ->pluck('total', 'status')
      ->toArray();

    return [
      'year' => $year,
      'labels' => ['Dalam Proses', 'Revisi', 'Ditolak', 'Selesai / Terbit'],
      'data' => [
        ($rawStatus['diajukan'] ?? 0) + ($rawStatus['disetujui_kabid'] ?? 0),
        ($rawStatus['revisi_kabid'] ?? 0) + ($rawStatus['revisi_kadis'] ?? 0),
        ($rawStatus['ditolak_kabid'] ?? 0) + ($rawStatus['ditolak_kadis'] ?? 0),
        ($rawStatus['disetujui_kadis'] ?? 0),
      ]
    ];
  }

  /**
   * Statistik proporsi penugasan pegawai berdasarkan golongan
   * @return array
   */
  public function getGolonganStatistics()
  {
    $year = (int) date('Y');

    $stats = DB::table('penugasan_pegawai')
      ->join('surat_perjalanan_dinas', 'penugasan_pegawai.surat_id', '=', 'surat_perjalanan_dinas.id')
      ->join('users', 'penugasan_pegawai.user_id', '=', 'users.id')
      ->join('pangkat_golongans', 'users.pangkat_golongan_id', '=', 'pangkat_golongans.id')
      ->select('pangkat_golongans.golongan', DB::raw('count(*) as total'))
      ->whereYear('surat_perjalanan_dinas.created_at', $year)
      ->groupBy('pangkat_golongans.golongan')
      ->orderBy('pangkat_golongans.golongan')
      ->pluck('total', 'golongan')
      ->toArray();

    return [
      'year' => $year,
      'labels' => array_keys($stats) ?? ['Tidak Ada Data'],
      'data' => array_values($stats) ?? [0],
    ];
  }

  /**
   * Data kalender dari surat perjalanan dinas yang disetujui
   * @return array
   */
  public function getCalendarData()
  {
    $query = SuratPerjalananDinas::query()
      ->select(['id', 'nomor_surat_tugas', 'tanggal_mulai', 'tanggal_selesai', 'status'])
      ->whereIn('status', ['disetujui_kadis']);

    return $query->get()
      ->map(fn($surat) => [
        'id' => $surat->id,
        'title' => $surat->nomor_surat_tugas,
        'start' => $surat->tanggal_mulai,
        'end' => Carbon::parse($surat->tanggal_selesai)->addDay()->format('Y-m-d'),
        'url' => route('telaah-staf.show', $surat->id),
      ])
      ->toArray();
  }
}
