<?php

namespace App\Services;

use Mpdf\Mpdf;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\SuratPerjalananDinas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SuratPerjalananDinasService
{
  /**
   * Ambil semua data surat untuk datatables
   * 
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getAllSurats()
  {
    // query semua data surat perjalanan dinas
    $query = SuratPerjalananDinas::with(['pembuat']);

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
    return User::whereDoesntHave('roles', function ($q) {
      $q->where('name', 'super-admin');
    })
      ->orderBy('id', 'asc')
      ->get();
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

    // buat pesan status berdasarkan status penyetuju satu
    return match ($statusPenyetujuSatu) {
      'disetujui' => 'Disetujui',
      'ditolak' => 'Ditolak',
      'revisi' => 'Dikembalikan untuk direvisi',
    };
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
        $updateData['nomor_nota_dinas'] = $this->generateNomorNotaDinas();
        $updateData['nomor_surat_tugas'] = $this->generateNomorSuratTugas();
      }

      // update semua data sekaligus (1 query)
      $surat->update($updateData);

      // buat pesan status berdasarkan status penyetuju dua
      return match ($statusPenyetujuDua) {
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'revisi' => 'Dikembalikan untuk direvisi',
      };
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

    // inisialisasi mpdf
    $mpdf = new Mpdf([
      'format' => 'A4',
      'orientation' => 'P',
      'margin_left' => 10,
      'margin_right' => 10,
      'margin_top' => 10,
      'margin_bottom' => 10,
    ]);

    // render view untuk PDF
    $html = view('pdf.telaah-staf', compact('surat'))->render();
    $mpdf->WriteHTML($html);

    // generate nama file : Telaah_Staf_[nomor_telaahan]_[tanggal].pdf
    $filename = 'Telaah_Staf_' . $surat->nomor_telaahan . '_' . date('d-m-Y') . '.pdf';

    // kembalikan output PDF ke browser
    return $mpdf->Output($filename, 'I');
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

    // inisialisasi mpdf
    $mpdf = new Mpdf([
      'format' => 'A4',
      'orientation' => 'P',
      'margin_left' => 10,
      'margin_right' => 10,
      'margin_top' => 10,
      'margin_bottom' => 10,
    ]);

    // render view untuk PDF
    $html = view('pdf.nota-dinas', compact('surat'))->render();
    $mpdf->WriteHTML($html);

    // generate nama file
    $filename = 'Nota_Dinas_' . $surat->nomor_nota_dinas . '_' . date('d-m-Y') . '.pdf';

    // kembalikan output PDF ke browser
    return $mpdf->Output($filename, 'I');
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
      throw new \Exception('Nomor Surat Tugas belum diisi.');
    }

    // load relasi
    $surat->load(['pegawaiDitugaskan.pangkatGolongan']);

    // inisialisasi mpdf
    $mpdf = new Mpdf([
      'format' => 'A4',
      'orientation' => 'P',
      'margin_left' => 10,
      'margin_right' => 10,
      'margin_top' => 10,
      'margin_bottom' => 10,
    ]);

    // render view untuk PDF
    $html = view('pdf.surat-tugas', compact('surat'))->render();
    $mpdf->WriteHTML($html);

    // generate nama file : Surat_Tugas_[nomor_surat_tugas]_[tanggal].pdf
    $filename = 'Surat_Tugas_' . $surat->nomor_surat_tugas . '_' . date('d-m-Y') . '.pdf';

    // kembalikan output PDF ke browser
    return $mpdf->Output($filename, 'I');
  }

  /**
   * Status surat secara global berdasarkan status penyetuju satu dan dua
   * 
   * @param mixed $statusPenyetujuDua
   * @param mixed $statusPenyetujuSatu
   * 
   * @return string
   */
  private function calculateGlobalStatus($statusPenyetujuDua, $statusPenyetujuSatu)
  {
    // status ditolak
    if ($statusPenyetujuSatu === 'ditolak') {
      return 'ditolak_kabid';
    }
    if ($statusPenyetujuDua === 'ditolak') {
      return 'ditolak_kadis';
    }

    // status revisi
    if ($statusPenyetujuSatu === 'revisi') {
      return 'revisi_kabid';
    }
    if ($statusPenyetujuDua === 'revisi') {
      return 'revisi_kadis';
    }

    // jika keduanya mensetujui -> disetujui kadis (surat tugas siap diterbitkan)
    if ($statusPenyetujuSatu === 'disetujui' && $statusPenyetujuDua === 'disetujui') {
      return 'disetujui_kadis';
    }

    // jika penyetuju satu mensetujui, tapi penyetuju dua masih pending -> disetujui kabid
    if ($statusPenyetujuSatu === 'disetujui' && $statusPenyetujuDua === 'pending') {
      return 'disetujui_kabid';
    }

    // jika keduanya masih pending -> diajukan
    return 'diajukan';
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
   * Generate nomor nota dinas dengan format increment per tahun
   * Format: 0001/YYYY, 0002/YYYY, dst.
   * 
   * @return string
   */
  private function generateNomorNotaDinas()
  {
    $tahunSekarang = date('Y');

    // cari nomor terakhir di tahun yang sama
    $suratTerakhir = SuratPerjalananDinas::whereNotNull('nomor_nota_dinas')
      // cari surat yang sudah memiliki nomor_nota_dinas
      ->whereYear('created_at', $tahunSekarang)
      // urutkan dari yang terbaru
      ->orderBy('id', 'desc')
      ->first();

    // jika belum ada surat di tahun ini, mulai dari 1
    if (!$suratTerakhir) {
      $nomorUrut = 1;
    } else {
      // extract nomor urut dari nomor nota dinas terakhir
      // format: 0001/2025 -> ambil 0001
      preg_match('/(\d+)\/\d{4}/', $suratTerakhir->nomor_nota_dinas, $matches);
      $nomorUrut = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
    }

    // format nomor dengan 4 digit
    return sprintf('%04d/%s', $nomorUrut, $tahunSekarang);
  }

  /**
   * Generate nomor surat tugas dengan format increment per tahun
   * Format: 0001/YYYY, 0002/YYYY, dst.
   * 
   * @return string
   */
  private function generateNomorSuratTugas()
  {
    $tahunSekarang = date('Y');

    // cari nomor terakhir di tahun yang sama
    $suratTerakhir = SuratPerjalananDinas::whereNotNull('nomor_surat_tugas')
      // cari surat yang sudah memiliki nomor_surat_tugas
      ->whereYear('created_at', $tahunSekarang)
      // urutkan dari yang terbaru
      ->orderBy('id', 'desc')
      ->first();

    // jika belum ada surat di tahun ini, mulai dari 1
    if (!$suratTerakhir) {
      $nomorUrut = 1;
    } else {
      // extract nomor urut dari nomor surat terakhir
      // format: 0001/2025 -> ambil 0001
      preg_match('/(\d+)\/\d{4}/', $suratTerakhir->nomor_surat_tugas, $matches);
      $nomorUrut = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
    }

    // format nomor dengan 4 digit
    return sprintf('%04d/%s', $nomorUrut, $tahunSekarang);
  }
}
