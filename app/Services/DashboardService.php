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
  // konstanta
  const STATUS_APPROVED = 'disetujui_kadis';
  const EXCEPT_ROLE = 'super-admin';
  const RECENT_LIMIT = 5;

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

  /**
   * Total surat perjalanan dinas dalam satu tahun
   * @param int $year
   * 
   * @return int
   */
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
      $query->where('name', self::EXCEPT_ROLE);
    })->count();
  }

  /**
   * 5 surat perjalanan dinas terakhir yang baru dibuat
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getRecentSurat()
  {
    return SuratPerjalananDinas::select([
      'id',
      'nomor_telaahan',
      'tanggal_telaahan',
      'status',
      'pembuat_id'
    ])
      ->with('pembuat:id,nama_lengkap')
      ->latest()
      ->take(self::RECENT_LIMIT)
      ->get();
  }

  /**
   * 5 aktivitas terakhir
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getRecentActivities()
  {
    return Activity::select([
      'id',
      'causer_id',
      'causer_type',
      'description',
      'created_at',
      'event'
    ])
      ->with('causer:id,email')
      ->latest()
      ->take(self::RECENT_LIMIT)
      ->get();
  }

  /**
   * Statistik intensitas surat perjalanan dinas per bulan dalam satu tahun
   * @return array
   */
  public function getIntensityStatistics()
  {
    $year = (int) date("Y");

    // menghitung jumlah surat per bulan berdasarkan tanggal_mulai
    $rawData = SuratPerjalananDinas::query()
      ->select(DB::raw('EXTRACT(MONTH FROM tanggal_mulai) as month'), DB::raw('count(*) as total'))
      ->where('status', self::STATUS_APPROVED)
      ->whereYear('tanggal_mulai', $year)
      ->groupBy(DB::raw('EXTRACT(MONTH FROM tanggal_mulai)'))
      ->orderBy('month')
      ->pluck('total', 'month')
      ->toArray();

    // inisialisasi array untuk 12 bulan dengan nilai 0
    $monthlyData = array_fill(1, 12, 0);

    // isi data dari query
    foreach ($rawData as $month => $total) {
      $monthlyData[(int)$month] = $total;
    }

    return [
      'year' => $year,
      'labels' => [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'Mei',
        'Jun',
        'Jul',
        'Agu',
        'Sep',
        'Okt',
        'Nov',
        'Des'
      ],
      'data' => array_values($monthlyData),
    ];
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
   * Hanya ambil surat dengan status = disetujui_kadis
   * @return array
   */
  public function getGolonganStatistics()
  {
    $year = (int) date('Y');

    $stats = User::query()
      ->select('pangkat_golongans.golongan', DB::raw('COUNT(penugasan_pegawai.id) as total'))
      ->join('pangkat_golongans', 'users.pangkat_golongan_id', '=', 'pangkat_golongans.id')
      ->join('penugasan_pegawai', 'users.id', '=', 'penugasan_pegawai.user_id')
      ->join('surat_perjalanan_dinas', 'penugasan_pegawai.surat_id', '=', 'surat_perjalanan_dinas.id')
      ->where('surat_perjalanan_dinas.status', self::STATUS_APPROVED)
      ->whereYear('surat_perjalanan_dinas.created_at', $year)
      ->groupBy('pangkat_golongans.golongan')
      ->orderBy('pangkat_golongans.golongan')
      ->pluck('total', 'golongan')
      ->toArray();

    return [
      'year' => $year,
      'labels' => !empty($stats) ? array_keys($stats) : ['Tidak Ada Data'],
      'data' => !empty($stats) ? array_values($stats) : [0],
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
      ->whereIn('status', [self::STATUS_APPROVED]);

    return $query->get()
      ->map(function ($surat) {
        return [
          'id' => $surat->id,
          'title' => $surat->nomor_surat_tugas,
          'start' => $surat->tanggal_mulai,
          'end' => Carbon::parse($surat->tanggal_selesai)->addDay()->format('Y-m-d'),
          'url' => route('telaah-staf.show', $surat->id),
        ];
      })
      ->toArray();
  }
}
