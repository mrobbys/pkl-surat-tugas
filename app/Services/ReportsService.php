<?php

namespace App\Services;

use App\Models\User;
use App\Models\SuratPerjalananDinas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class ReportsService
{
  /**
   * Generate Rekapitulasi Surat Telaahan Staf
   * @param array $validatedData
   * 
   * @return \Illuminate\Http\Response
   */
  public function generateRekapitulasiSuratTelaahanStaf(array $validatedData)
  {
    $tanggalAwal = $validatedData['tanggal_awal'];
    $tanggalAkhir = $validatedData['tanggal_akhir'];
    $fileSource = 'pdf.rekap-telaahan-staf';

    try {
      $data = SuratPerjalananDinas::with([
        'pembuat:id,nama_lengkap',
      ])
        ->whereBetween('tanggal_telaahan', [$tanggalAwal, $tanggalAkhir])
        ->orderBy('tanggal_telaahan', 'asc')
        ->get();

      // Cek apakah ada data
      if ($data->isEmpty()) {
        throw new \Exception('Tidak ada data surat pada rentang tanggal yang dipilih.');
      }

      // log activity
      $this->logReportActivity('Rekapitulasi Surat Telaahan Staf', $validatedData);

      // Generate PDF
      return $this->generatePDF($data, $tanggalAwal, $tanggalAkhir, $fileSource, 'Surat Telaahan Staf');
    } catch (\Exception $e) {
      throw new \Exception('Gagal generate laporan: ' . $e->getMessage());
    }
  }

  /**
   * Generate Rekapitulasi Surat Nota Dinas
   * @param array $validatedData
   * 
   * @return \Illuminate\Http\Response
   */
  public function generateRekapitulasiSuratNotaDinas(array $validatedData)
  {
    $tanggalAwal = $validatedData['tanggal_awal'];
    $tanggalAkhir = $validatedData['tanggal_akhir'];
    $fileSource = 'pdf.rekap-nota-dinas';

    try {
      $data = SuratPerjalananDinas::with([
        'pembuat:id,nama_lengkap',
      ])
        ->whereNotNull('nomor_nota_dinas')
        ->where('nomor_nota_dinas', '!=', '')
        ->where('status', 'disetujui_kadis')
        ->whereDate('tanggal_status_dua', '>=', $tanggalAwal)
        ->whereDate('tanggal_status_dua', '<=', $tanggalAkhir)
        ->orderBy('tanggal_status_dua', 'asc')
        ->get();

      if ($data->isEmpty()) {
        throw new \Exception('Tidak ada data nota dinas pada rentang tanggal tersebut.');
      }

      // activity log
      $this->logReportActivity('Rekapitulasi Surat Nota Dinas', $validatedData);

      // Generate PDF
      return $this->generatePDF($data, $tanggalAwal, $tanggalAkhir, $fileSource, 'Surat Nota Dinas');
    } catch (\Exception $e) {
      throw new \Exception('Gagal generate laporan: ' . $e->getMessage());
    }
  }

  /**
   * Generate Rekapitulasi Surat Tugas
   * @param array $validatedData
   * 
   * @return \Illuminate\Http\Response
   */
  public function generateRekapitulasiSuratTugas(array $validatedData)
  {
    $tanggalAwal = $validatedData['tanggal_awal'];
    $tanggalAkhir = $validatedData['tanggal_akhir'];
    $fileSource = 'pdf.rekap-surat-tugas';

    try {
      $data = SuratPerjalananDinas::with([
        'pembuat:id,nama_lengkap',
        'pegawaiDitugaskan:id,nama_lengkap',
      ])
        ->whereNotNull('nomor_surat_tugas')
        ->where('nomor_surat_tugas', '!=', '')
        ->where('status', 'disetujui_kadis')
        ->whereDate('tanggal_status_dua', '>=', $tanggalAwal)
        ->whereDate('tanggal_status_dua', '<=', $tanggalAkhir)
        ->orderBy('tanggal_status_dua', 'asc')
        ->get();

      if ($data->isEmpty()) {
        throw new \Exception('Tidak ada data surat tugas pada rentang tanggal tersebut.');
      }

      // activity log
      $this->logReportActivity('Rekapitulasi Surat Tugas', $validatedData);

      // Generate PDF
      return $this->generatePDF($data, $tanggalAwal, $tanggalAkhir, $fileSource, 'Surat Tugas');
    } catch (\Exception $e) {
      throw new \Exception('Gagal generate laporan: ' . $e->getMessage());
    }
  }

  /**
   * Generate Rekapitulasi Aktivitas Pegawai
   * @param array $validatedData
   * 
   * @return \Illuminate\Http\Response
   */
  public function generateRekapitulasiAktivitasPegawai(array $validatedData)
  {
    $tanggalAwal = $validatedData['tanggal_awal'];
    $tanggalAkhir = $validatedData['tanggal_akhir'];
    $fileSource = 'pdf.rekap-aktivitas-pegawai';

    try {
      $users = User::with(['pangkatGolongan'])
        ->withCount([
          // hitung jumlah surat yang dibuat oleh user
          'suratPerjalananDinasDibuat as jumlah_surat_dibuat' => function ($query) use ($tanggalAwal, $tanggalAkhir) {
            // filter hanya surat dengan tanggal_telaahan dalam rentang tanggal yang dipilih dan filter status =  disetujui_kadis
            $query->whereBetween('tanggal_telaahan', [$tanggalAwal, $tanggalAkhir])
              ->where('status', 'disetujui_kadis')
            ;
          },
          // hitung jumlah penugasan yang diterima oleh user
          'penugasan as jumlah_ditugaskan' => function ($query) use ($tanggalAwal, $tanggalAkhir) {
            // filter hanya penugasan yang tanggal_mulai atau tanggal_selesai dalam rentang tanggal yang dipilih
            $query->where(function ($q) use ($tanggalAwal, $tanggalAkhir) {
              $q->whereBetween('tanggal_mulai', [$tanggalAwal, $tanggalAkhir])
                ->orWhereBetween('tanggal_selesai', [$tanggalAwal, $tanggalAkhir]);
            })
              ->where('status', 'disetujui_kadis');
          }
        ])
        ->with(['penugasan' => function ($query) use ($tanggalAwal, $tanggalAkhir) {
          $query->where(function ($q) use ($tanggalAwal, $tanggalAkhir) {
            $q->whereBetween('tanggal_mulai', [$tanggalAwal, $tanggalAkhir])
              ->orWhereBetween('tanggal_selesai', [$tanggalAwal, $tanggalAkhir]);
          })
            ->where('status', 'disetujui_kadis');
        }])
        ->whereDoesntHave('roles', function ($query) {
          $query->where('name', 'super-admin');
        })
        // filter hanya user yang memiliki aktivitas dalam rentang tanggal yang dipilih
        ->where(function ($query) use ($tanggalAwal, $tanggalAkhir) {
          // user yang membuat surat dalam rentang tanggal
          $query->whereHas('suratPerjalananDinasDibuat', function ($q) use ($tanggalAwal, $tanggalAkhir) {
            $q->whereBetween('tanggal_telaahan', [$tanggalAwal, $tanggalAkhir])
              ->where('status', 'disetujui_kadis')
            ;
          })
            // atau user yang ditugaskan dalam rentang tanggal
            ->orWhereHas('penugasan', function ($q) use ($tanggalAwal, $tanggalAkhir) {
              $q->where(function ($subQ) use ($tanggalAwal, $tanggalAkhir) {
                $subQ->whereBetween('tanggal_mulai', [$tanggalAwal, $tanggalAkhir])
                  ->orWhereBetween('tanggal_selesai', [$tanggalAwal, $tanggalAkhir]);
              })
                ->where('status', 'disetujui_kadis');
            });
        })
        ->orderBy('nama_lengkap', 'asc')
        ->get();

      if ($users->isEmpty()) {
        throw new \Exception('Tidak ada data aktivitas pegawai pada rentang tanggal tersebut.');
      }

      $data = $users->map(function ($user) {
        $totalDurasi = $user->penugasan->sum(function ($surat) {
          return Carbon::parse($surat->tanggal_mulai)->diffInDays(Carbon::parse($surat->tanggal_selesai)) + 1;
        });

        return [
          'nip' => $user->nip,
          'nama_lengkap' => $user->nama_lengkap,
          'pangkat' => $user->pangkatGolongan?->pangkat ?? '-',
          'golongan' => $user->pangkatGolongan
            ? "{$user->pangkatGolongan->golongan}/{$user->pangkatGolongan->ruang}"
            : '-',
          'jabatan' => $user->jabatan ?? '-',
          'jumlah_surat_dibuat' => $user->jumlah_surat_dibuat,
          'jumlah_ditugaskan' => $user->jumlah_ditugaskan,
          'total_durasi' => $totalDurasi,
        ];
      })->filter(function ($item) {
        return $item['jumlah_surat_dibuat'] > 0 || $item['jumlah_ditugaskan'] > 0;
      })->values();

      if ($data->isEmpty()) {
        throw new \Exception('Tidak ada data aktivitas pegawai pada rentang tanggal tersebut.');
      }

      // activity log
      $this->logReportActivity('Rekapitulasi Aktivitas Pegawai', $validatedData);

      // Generate PDF
      return $this->generatePDF($data, $tanggalAwal, $tanggalAkhir, $fileSource, 'Aktivitas Pegawai');
    } catch (\Exception $e) {
      throw new \Exception('Gagal generate laporan: ' . $e->getMessage());
    }
  }

  /**
   * Generate Master Pegawai
   * @param array $validatedData
   * 
   * @return \Illuminate\Http\Response
   */
  public function generateMasterPegawai(array $validatedData)
  {
    $tanggalAwal = $validatedData['tanggal_awal'];
    $tanggalAkhir = $validatedData['tanggal_akhir'];
    $fileSource = 'pdf.master-pegawai';

    try {
      $data = User::with(['pangkatGolongan'])
        ->whereDoesntHave('roles', function ($query) {
          $query->where('name', 'super-admin');
        })
        ->whereDate('created_at', '>=', $tanggalAwal)
        ->whereDate('created_at', '<=', $tanggalAkhir)
        ->orderBy('nama_lengkap', 'asc')
        ->get();

      if ($data->isEmpty()) {
        throw new \Exception('Tidak ada data pegawai.');
      }

      // activity log
      $this->logReportActivity('Master Pegawai', $validatedData);

      // Generate PDF
      return $this->generatePDF($data, $tanggalAwal, $tanggalAkhir, $fileSource, 'Master Pegawai');
    } catch (\Exception $e) {
      throw new \Exception('Gagal generate laporan: ' . $e->getMessage());
    }
  }

  /**
   * Generate PDF Rekapitulasi Surat Telaahan Staf
   * @param mixed $data : data surat telaahan staf
   * @param mixed $tanggalAwal : tanggal awal
   * @param mixed $tanggalAkhir : tanggal akhir
   * @param mixed $fileSource : sumber file view untuk pdf
   * 
   * @return \Illuminate\Http\Response
   */
  public function generatePDF($data, $tanggalAwal, $tanggalAkhir, $fileSource, $reportType)
  {
    $mpdf = new Mpdf([
      'mode' => 'utf-8',
      'format' => 'A4',
      'orientation' => 'L',
      'margin_top' => 10,
      'margin_bottom' => 10,
      'margin_left' => 10,
      'margin_right' => 10,
    ]);

    // ambil data user yang sedang login
    $user = Auth::user();

    $html = view($fileSource, [
      'data' => $data,
      'tanggalAwal' => Carbon::parse($tanggalAwal)->translatedFormat('d F Y'),
      'tanggalAkhir' => Carbon::parse($tanggalAkhir)->translatedFormat('d F Y'),
      'tanggalCetak' => Carbon::now()->translatedFormat('d F Y'),
      'user' => $user->nama_lengkap,
    ])->render();

    $mpdf->WriteHTML($html);

    $fileName = 'Rekapitulasi_' . str_replace(' ', '_', $reportType) . '_' .
      Carbon::parse($tanggalAwal)->format('d-m-Y') . '_sd_' .
      Carbon::parse($tanggalAkhir)->format('d-m-Y') . '.pdf';

    return response($mpdf->Output($fileName, 'S'))
      ->header('Content-Type', 'application/pdf')
      ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
  }

  /**
   * Log activity untuk report yang di-generate
   * 
   * @param string $reportType
   * @param array $validatedData
   */
  private function logReportActivity(string $reportType, array $validatedData)
  {
    activity()
      ->causedBy(Auth::user())
      ->withProperties([
        'report_type' => $reportType,
        'tanggal_awal' => $validatedData['tanggal_awal'] ?? null,
        'tanggal_akhir' => $validatedData['tanggal_akhir'] ?? null,
        'periode' => isset($validatedData['tanggal_awal']) && isset($validatedData['tanggal_akhir'])
          ? Carbon::parse($validatedData['tanggal_awal'])->format('d/m/Y') . ' - ' . Carbon::parse($validatedData['tanggal_akhir'])->format('d/m/Y')
          : 'Semua Data',
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'log_name' => 'reports',
      ])
      ->log("Generate laporan: {$reportType}");
  }
}
