<?php

namespace App\Services;

use Mpdf\Mpdf;
use App\Models\User;
use Mpdf\MpdfException;
use Illuminate\Support\Facades\DB;
use App\Models\SuratPerjalananDinas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SuratPerjalananDinasService
{
  // final status
  private const FINAL_STATUSES = ['disetujui_kadis', 'ditolak_kabid', 'ditolak_kadis'];

  /**
   * Ambil semua data surat untuk datatables
   * 
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getAllSurats()
  {
    // query semua data surat perjalanan dinas
    // $query = SuratPerjalananDinas::with(['pembuat']);
    $query = SuratPerjalananDinas::select(['id', 'nomor_telaahan', 'tanggal_telaahan', 'pembuat_id', 'status'])->with('pembuat:id,nama_lengkap');

    // filter berdasarkan user yang sedang login
    $user = Auth::user();

    // jika user tidak memiliki permissions 'approve telaah staf level 1' dan 'approve telaah staf level 2' tampilkan hanya data milik user yang login
    if (!$user->hasAnyPermission(['approve telaah staf level 1', 'approve telaah staf level 2'])) {
      $query->where('pembuat_id', $user->id);
    }

    // jika user memiliki permissions 'approve telaah staf level 1' dan 'approve telaah staf level 2', tampilkan semua data
    $data = $query->orderByDesc('created_at')->get();
    return $data;
  }

  /**
   * Ambil data pegawai untuk select option (kecuali superadmin)
   * 
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getPegawaisForSelect()
  {
    // ambil data user yang bukan super-admin dan urutkan berdasarkan id asc
    return Cache::remember('pegawai_list', 86400, function () {
      return User::select(['id', 'nama_lengkap', 'nip'])
        ->whereDoesntHave('roles', function ($q) {
          $q->where('name', 'super-admin');
        })
        ->orderBy('nama_lengkap', 'asc')
        ->get();
    });
  }

  /**
   * Simpan data telaah staf beserta pegawai yang ditugaskan
   * 
   * @param array $data
   * 
   * @return \App\Models\SuratPerjalananDinas
   */
  public function storeTelaahStaf(array $data)
  {
    // menggunakan db transaction agar proses simpan data ke table surat_perjalanan_dinas dan penugasan_pegawai berhasil
    return DB::transaction(function () use ($data) {
      // simpan data telaah staf
      $surat = SuratPerjalananDinas::create([
        'kepada_yth' => $data['kepada_yth'],
        'dari' => $data['dari'],
        'nomor_telaahan' => $data['nomor_telaahan'],
        'tanggal_telaahan' => $data['tanggal_telaahan'],
        'perihal_kegiatan' => $data['perihal_kegiatan'],
        'tempat_pelaksanaan' => $data['tempat_pelaksanaan'],
        'tanggal_mulai' => $data['tanggal_mulai'],
        'tanggal_selesai' => $data['tanggal_selesai'],
        'dasar_telaahan' => $data['dasar_telaahan'],
        'isi_telaahan' => $data['isi_telaahan'],

        // id user yang sedang login sebagai pembuat
        'pembuat_id' => Auth::id(),
      ]);
      // simpan relasi many to many ke tabel penugasan_pegawai
      $surat->pegawaiDitugaskan()->sync($data['pegawais']);

      return $surat;
    });
  }

  /**
   * Ambil detail telaah staf dengan relasi
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * 
   * @return array
   */
  public function getTelaahStafDetail(SuratPerjalananDinas $surat)
  {
    // cek apakah user yang sedang login adalah pembuat surat
    // kecuali untuk role = super-admin, permission = 'approve telaah staf level 1', 'approve telaah staf level 2'
    $user = Auth::user();
    $isOwner = $surat->pembuat_id === $user->id;
    $isApprover = $user->hasAnyPermission(['approve telaah staf level 1', 'approve telaah staf level 2']);
    $isSuperAdmin = $user->hasRole('super-admin');
    if (!$isOwner && !$isApprover && !$isSuperAdmin) {
      abort(403, 'Anda tidak memiliki akses untuk melihat detail surat ini.');
    }

    // ammbil data surat dengan relasi pegawaiDitugaskan dan pembuat
    $surat->load(['pegawaiDitugaskan.pangkatGolongan', 'pembuat', 'penyetujuSatu', 'penyetujuDua']);

    // tambahkan data pegawais ke array data
    $data = $surat->toArray();
    $data['pegawais'] = $surat->pegawaiDitugaskan;
    $data['penyetuju_satu_nama'] = $surat->penyetujuSatu ? $surat->penyetujuSatu->nama_lengkap : null;
    $data['penyetuju_dua_nama'] = $surat->penyetujuDua ? $surat->penyetujuDua->nama_lengkap : null;

    // format tanggal status penyetuju
    $data['tanggal_status_satu_formatted'] = $this->formatTanggalStatusPenyetuju($surat->tanggal_status_satu);
    $data['tanggal_status_dua_formatted'] = $this->formatTanggalStatusPenyetuju($surat->tanggal_status_dua);


    return $data;
  }

  /**
   * Ambil data telaah staf untuk form edit
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * 
   * @return array
   */
  public function getTelaahStafForEdit(SuratPerjalananDinas $surat)
  {
    // ambil data surat dengan relasi pegawaiDitugaskan dan pembuat
    $surat->load(['pegawaiDitugaskan.pangkatGolongan', 'pembuat', 'pembuat.pangkatGolongan']);

    // tambahkan data pegawais ke array data
    $data = $surat->toArray();
    $data['pegawais'] = $surat->pegawaiDitugaskan;

    return $data;
  }

  /**
   * Update data telaah staf
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * @param array $data
   * 
   * @return \App\Models\SuratPerjalananDinas
   */
  public function updateTelaahStaf(SuratPerjalananDinas $surat, array $data)
  {
    // menggunakan db transaction agar proses update data ke table surat_perjalanan_dinas dan penugasan_pegawai berhasil
    return DB::transaction(function () use ($surat, $data) {
      // update data telaah staf
      $surat->update([
        'kepada_yth' => $data['kepada_yth'],
        'dari' => $data['dari'],
        'nomor_telaahan' => $data['nomor_telaahan'],
        'tanggal_telaahan' => $data['tanggal_telaahan'],
        'perihal_kegiatan' => $data['perihal_kegiatan'],
        'tempat_pelaksanaan' => $data['tempat_pelaksanaan'],
        'tanggal_mulai' => $data['tanggal_mulai'],
        'tanggal_selesai' => $data['tanggal_selesai'],
        'dasar_telaahan' => $data['dasar_telaahan'],
        'isi_telaahan' => $data['isi_telaahan'],

        // id user yang sedang login sebagai pembuat
        'pembuat_id' => Auth::id(),
      ]);
      // simpan relasi many to many ke tabel penugasan_pegawai
      $surat->pegawaiDitugaskan()->sync($data['pegawais']);

      // jika status = 'revisi_kabid' maka update status surat menjadi 'diajukan'
      if ($surat->status === 'revisi_kabid') {
        $surat->update(['status' => 'diajukan']);
      }

      return $surat;
    });
  }

  /**
   * Hapus data telaah staf
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * 
   * @return bool
   */
  public function destroyTelaahStaf(SuratPerjalananDinas $surat)
  {
    $surat->delete();
  }

  /**
   * Approve telaah staf level 1
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * @param array $data
   * 
   * @return string Pesan status approval
   */
  public function approveSatuTelaahStaf(SuratPerjalananDinas $surat, array $data)
  {
    // tangkap status dari request
    $statusPenyetujuSatu = $data['status'];

    // update status penyetuju satu
    $surat->update([
      'status_penyetuju_satu' => $statusPenyetujuSatu,
      'penyetuju_satu_id' => Auth::id(),
      'tanggal_status_satu' => now(),
      'catatan_satu' => $data['catatan'],
      'status' => $this->calculateGlobalStatus($surat->status_penyetuju_dua, $statusPenyetujuSatu),
    ]);

    return $this->getApprovalMessage($statusPenyetujuSatu);
  }

  /**
   * Approve telaah staf level 2
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * @param array $data
   * 
   * @return string Pesan status approval
   */
  public function approveDuaTelaahStaf(SuratPerjalananDinas $surat, array $data)
  {
    // gunakan db transaction agar proses update data ke field status penyetuju dua dan field nomor_surat_tugas berhasil
    return DB::transaction(function () use ($surat, $data) {
      // tangkap status dari request
      $statusPenyetujuDua = $data['status'];

      $statusGlobal = $this->calculateGlobalStatus($statusPenyetujuDua, $surat->status_penyetuju_satu);

      // update status penyetuju dua
      $updateData = [
        'status_penyetuju_dua' => $statusPenyetujuDua,
        'penyetuju_dua_id' => Auth::id(),
        'tanggal_status_dua' => now(),
        'catatan_dua' => $data['catatan'],
        'status' => $statusGlobal,
      ];

      // jika status akhir adalah disetujui_kadis dan nomor_surat_tugas belum ada, isi nomor surat tugas
      if ($statusGlobal === 'disetujui_kadis' && !$surat->nomor_surat_tugas) {
        $updateData['nomor_nota_dinas'] = $this->generateNomorSurat('nota_dinas');
        $updateData['nomor_surat_tugas'] = $this->generateNomorSurat('surat_tugas');
      }

      // update semua data sekaligus (1 query)
      $surat->update($updateData);

      return $this->getApprovalMessage($statusPenyetujuDua);
    });
  }

  /**
   * Generate PDF telaah staf
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * 
   * @return mixed
   */
  public function generatePDFTelaahStaf(SuratPerjalananDinas $surat)
  {
    // load relasi
    $surat->load(['pegawaiDitugaskan.pangkatGolongan', 'pembuat', 'pembuat.pangkatGolongan']);

    return $this->generatePDF($surat, 'pdf.telaah-staf', 'Telaah_Staf');
  }

  /**
   * Generate PDF nota dinas
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * 
   * @return mixed
   */
  public function generatePDFNotaDinas(SuratPerjalananDinas $surat)
  {
    if (!$surat->nomor_nota_dinas) {
      throw new \Exception('Nomor Nota Dinas belum diisi.');
    }

    if ($surat->status !== 'disetujui_kadis') {
      throw new \InvalidArgumentException('Nota Dinas hanya dapat dicetak setelah disetujui oleh Kadis.');
    }

    return $this->generatePDF($surat, 'pdf.nota-dinas', 'Nota_Dinas', $surat->nomor_nota_dinas);
  }

  /**
   * Generate PDF surat tugas
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * 
   * @return mixed
   */
  public function generatePDFSuratTugas(SuratPerjalananDinas $surat)
  {
    if (!$surat->nomor_surat_tugas) {
      throw new \Exception('Nomor Surat Tugas belum diterbitkan.');
    }

    if ($surat->status !== 'disetujui_kadis') {
      throw new \InvalidArgumentException('Surat Tugas hanya dapat dicetak setelah disetujui oleh Kadis.');
    }

    // load relasi
    $surat->load(['pegawaiDitugaskan.pangkatGolongan']);

    return $this->generatePDF($surat, 'pdf.surat-tugas', 'Surat_Tugas', $surat->nomor_surat_tugas);
  }

  private function generatePDF(SuratPerjalananDinas $surat, string $viewFile, string $filePrefix, ?string $nomorSurat = null)
  {
    try {
      $mpdf = new Mpdf([
        'format' => 'A4',
        'orientation' => 'P',
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 10,
        'margin_bottom' => 10,
      ]);

      $html = view($viewFile, compact('surat'))->render();
      $mpdf->WriteHTML($html);

      $nomorForFile = $nomorSurat ?? $surat->nomor_telaahan;
      $filename = "{$filePrefix}_{$nomorForFile}_" . date('d-m-Y') . '.pdf';

      return response($mpdf->Output($filename, 'I'))
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    } catch (MpdfException $e) {
      throw new \Exception('Gagal generate PDF: ' . $e->getMessage());
    }
  }

  /**
   * Cek apakah status surat adalah final status
   * @param SuratPerjalananDinas $surat
   * 
   * @return bool
   */
  public function isFinalStatus(SuratPerjalananDinas $surat)
  {
    return in_array($surat->status, self::FINAL_STATUSES);
  }

  /**
   * Status surat secara global berdasarkan status penyetuju satu dan dua
   * 
   * @param mixed $statusPenyetujuDua
   * @param mixed $statusPenyetujuSatu
   * 
   * @return string
   */
  private function calculateGlobalStatus(?string $statusPenyetujuDua, ?string $statusPenyetujuSatu)
  {
    // Ditolak
    if ($statusPenyetujuSatu === 'ditolak') return 'ditolak_kabid';
    if ($statusPenyetujuDua === 'ditolak') return 'ditolak_kadis';

    // Revisi
    if ($statusPenyetujuSatu === 'revisi') return 'revisi_kabid';
    if ($statusPenyetujuDua === 'revisi') return 'revisi_kadis';

    // Disetujui keduanya
    if ($statusPenyetujuSatu === 'disetujui' && $statusPenyetujuDua === 'disetujui') {
      return 'disetujui_kadis';
    }

    // Disetujui level 1, pending level 2
    if ($statusPenyetujuSatu === 'disetujui' && $statusPenyetujuDua === 'pending') {
      return 'disetujui_kabid';
    }

    return 'diajukan';
  }

  /**
   * Buat pesan approval berdasarkan status
   * @param string $status
   * 
   * @return string
   */
  private function getApprovalMessage(string $status)
  {
    return match ($status) {
      'disetujui' => 'Disetujui',
      'ditolak' => 'Ditolak',
      'revisi' => 'Dikembalikan untuk direvisi',
      default => 'Diproses',
    };
  }

  /**
   * Format tanggal status penyetuju
   * 
   * @param mixed $tanggal
   * 
   * @return string
   */
  public function formatTanggalStatusPenyetuju($tanggal)
  {
    return $tanggal
      ? \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('j F Y H:i:s')
      : '-';
  }

  /**
   * Generate nomor surat dengan format increment per tahun
   * Format: 0001/YYYY, 0002/YYYY, dst.
   * @param string $type
   * 
   * @return string
   */
  private function generateNomorSurat(string $type)
  {
    $tahun = date('Y');
    $columnName = $type === 'nota_dinas' ? 'nomor_nota_dinas' : 'nomor_surat_tugas';

    // ✅ Use lockForUpdate untuk prevent race condition
    $suratTerakhir = SuratPerjalananDinas::whereNotNull($columnName)
      ->whereYear('created_at', $tahun)
      ->orderByDesc('id')
      ->lockForUpdate()
      ->first();

    $nomorUrut = 1;

    if ($suratTerakhir) {
      preg_match('/(\d+)\/\d{4}/', $suratTerakhir->{$columnName}, $matches);
      $nomorUrut = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
    }

    return sprintf('%04d/%s', $nomorUrut, $tahun);
  }
}
