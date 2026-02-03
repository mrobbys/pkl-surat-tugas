<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveTelaahStafRequest;
use Illuminate\Http\Request;
use App\Models\SuratPerjalananDinas;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTelaahStafRequest;
use App\Http\Requests\UpdateTelaahStafRequest;
use App\Services\SuratPerjalananDinasService;
use Illuminate\Support\Facades\Log;

class SuratPerjalananDinasController extends Controller
{
  // service surat perjalanan dinas
  protected $suratService;

  // inject service via constructor
  public function __construct(SuratPerjalananDinasService $suratService)
  {
    $this->suratService = $suratService;
  }

  /**
   * Tampil data surat
   * 
   * @param \Illuminate\Http\Request $request
   * 
   * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
   */
  public function indexSurat(Request $request)
  {
    if ($request->ajax()) {
      try {
        $data = $this->suratService->getAllSurats();
        return response()->json(['data' => $data]);
      } catch (\Exception $e) {
        return response()->json([
          'status' => 'error',
          'message' => 'Gagal memuat data surat.',
        ], 500);
      }
    }
    return view('pages.surat.index');
  }

  /**
   * Ambil data user untuk create telaah staf
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function createTelaahStaf()
  {
    // cek apakah user memiliki permission 'create telaah staf'
    if (!Auth::user()->can('create telaah staf')) {
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Anda tidak memiliki permission untuk membuat Telaah Staf!',
      ], 403);
    }

    $pegawais = $this->suratService->getPegawaisForSelect();
    return view('pages.surat.form-telaah-staf', [
      'mode' => 'create',
      'pegawais' => $pegawais,
      'surat' => null,
    ]);
  }

  /**
   * Simpan data telaah staf
   * 
   * @param \App\Http\Requests\StoreTelaahStafRequest $request
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function storeTelaahStaf(StoreTelaahStafRequest $request)
  {
    // cek apakah user memiliki permission 'create telaah staf'
    if (!Auth::user()->can('create telaah staf')) {
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Anda tidak memiliki permission untuk membuat Telaah Staf!',
      ], 403);
    }

    try {
      // simpan data telaah staf via service
      $this->suratService->storeTelaahStaf($request->validated());

      // jika semua proses berhasil, kembalikan response success
      return response()->json([
        'status' => 'success',
        'icon' => 'success',
        'title' => 'Berhasil',
        'text' => 'Telaah Staf berhasil dibuat!',
      ], 201);
    } catch (\Exception $e) {
      // jika terjadi error, kembalikan response error
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Telaah Staf gagal dibuat. Silakan coba lagi nanti.',
      ], 500);
    }
  }

  /**
   * Detail telaah staf
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function showTelaahStaf(SuratPerjalananDinas $surat)
  {
    // cek apakah user memiliki permission 'view telaah staf'
    if (!Auth::user()->can('view telaah staf')) {
      return redirect()->route('dashboard')->with('alert', [
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Anda tidak memiliki izin untuk melihat telaah staf.',
      ]);
    }

    try {
      $data = $this->suratService->getTelaahStafDetail($surat);
      return view('pages.surat.form-telaah-staf', [
        'mode' => 'show',
        'pegawais' => $data['pegawais'],
        'surat' => $surat,
        'data' => $data,
      ]);
    } catch (\Exception $e) {
      return redirect()->route('surat.index')->with('alert', [
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Gagal memuat detail telaah staf.',
      ]);
    }
  }

  /**
   * Edit telaah staf
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function editTelaahStaf(SuratPerjalananDinas $surat)
  {
    // cek apakah user memiliki permission 'edit telaah staf'
    if (!Auth::user()->can('edit telaah staf')) {
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Anda tidak memiliki izin untuk mengedit telaah staf.',
      ], 403);
    }

    // validasi status surat
    if ($this->suratService->isFinalStatus($surat)) {
      return redirect()->route('surat.index')->with('alert', [
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Telaah Staf tidak dapat diedit karena status surat sudah final.',
      ]);
    }

    try {
      $data = $this->suratService->getTelaahStafForEdit($surat);
      $pegawais = $this->suratService->getPegawaisForSelect();

      return view('pages.surat.form-telaah-staf', [
        'mode' => 'edit',
        'pegawais' => $pegawais,
        'surat' => $surat,
        'data' => $data,
      ]);
    } catch (\Exception $e) {
      return redirect()->route('surat.index')->with('alert', [
        'icon' => 'error',
        'title' => 'Error',
        'text' => 'Gagal memuat form edit.',
      ]);
    }
  }

  /**
   * Update telaah staf
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * @param \App\Http\Requests\UpdateTelaahStafRequest $request
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateTelaahStaf(SuratPerjalananDinas $surat, UpdateTelaahStafRequest $request)
  {
    // cek apakah user memiliki permission 'edit telaah staf'
    if (!Auth::user()->can('edit telaah staf')) {
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Anda tidak memiliki izin untuk mengedit telaah staf.',
      ], 403);
    }

    try {
      // update data telaah staf via service
      $this->suratService->updateTelaahStaf($surat, $request->validated());

      // jika semua proses berhasil, kembalikan response success
      return response()->json([
        'status' => 'success',
        'icon' => 'success',
        'title' => 'Berhasil',
        'text' => 'Telaah Staf berhasil diupdate!',
      ], 200);
    } catch (\Exception $e) {
      // jika terjadi error, kembalikan response error
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Telaah Staf gagal diupdate. Silakan coba lagi nanti.',
      ], 500);
    }
  }

  /**
   * Hapus telaah staf
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroyTelaahStaf(SuratPerjalananDinas $surat)
  {
    // cek apakah user memiliki permission 'delete telaah staf'
    if (!Auth::user()->can('delete telaah staf')) {
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Anda tidak memiliki izin untuk menghapus telaah staf.',
      ], 403);
    }

    // ✅ Validasi status surat
    if ($this->suratService->isFinalStatus($surat)) {
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Telaah Staf tidak dapat dihapus karena status surat sudah final.',
      ], 403);
    }

    try {
      $this->suratService->destroyTelaahStaf($surat);

      return response()->json([
        'status' => 'success',
        'icon' => 'success',
        'title' => 'Berhasil',
        'text' => 'Telaah Staf berhasil dihapus!',
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Telaah Staf gagal dihapus. Silakan coba lagi.',
      ], 500);
    }
  }

  /**
   * Approve telaah staf level 1
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * @param \App\Http\Requests\ApproveTelaahStafRequest $request
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function approveSatuTelaahStaf(SuratPerjalananDinas $surat, ApproveTelaahStafRequest $request)
  {
    // cek apakah user memiliki permission 'approve telaah staf level 1'
    if (!Auth::user()->can('approve telaah staf level 1')) {
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Anda tidak memiliki izin untuk menyetujui telaah staf.',
      ], 403);
    }

    try {
      // approve telaah staf level 1 via service
      $statusPenyetujuSatu = $this->suratService->approveSatuTelaahStaf($surat, $request->validated());

      // kembalikan response success
      return response()->json([
        'status' => 'success',
        'icon' => 'success',
        'title' => 'Berhasil',
        'text' => "Telaah Staf berhasil {$statusPenyetujuSatu}!",
      ], 200);
    } catch (\Exception $e) {
      // kembalikan response error
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Proses persetujuan gagal. Silakan coba lagi.',
      ], 500);
    }
  }

  /**
   * Approve telaah staf level 2
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * @param \App\Http\Requests\ApproveTelaahStafRequest $request
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function approveDuaTelaahStaf(SuratPerjalananDinas $surat, ApproveTelaahStafRequest $request)
  {
    // cek apakah user memiliki permission 'approve telaah staf level 2'
    if (!Auth::user()->can('approve telaah staf level 2')) {
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Anda tidak memiliki izin untuk menyetujui telaah staf.',
      ], 403);
    }

    try {
      // approve telaah staf level 2 via service
      $statusPenyetujuDua = $this->suratService->approveDuaTelaahStaf($surat, $request->validated());

      return response()->json([
        'status' => 'success',
        'icon' => 'success',
        'title' => 'Berhasil',
        'text' => "Telaah Staf berhasil {$statusPenyetujuDua}!",
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Proses persetujuan gagal. Silakan coba lagi.',
      ], 500);
    }
  }

  /**
   * Cetak PDF Telaah Staf
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * 
   * @return \Illuminate\Http\Response
   */
  public function cetakPDFTelaahStaf(SuratPerjalananDinas $surat)
  {
    // cek apakah user memiliki permission 'pdf telaah staf'
    if (!Auth::user()->can('pdf telaah staf')) {
      return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mencetak PDF telaah staf.');
    }

    try {
      return $this->suratService->generatePDFTelaahStaf($surat);
    } catch (\Exception $e) {
      return redirect()->back()->with('alert', [
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Gagal mencetak PDF. Silakan coba lagi.',
      ]);
    }
  }

  /**
   * Cetak PDF Nota Dinas
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * 
   * @return \Illuminate\Http\Response
   */
  public function cetakPDFNotaDinas(SuratPerjalananDinas $surat)
  {
    // cek apakah user memiliki permission 'pdf nota dinas'
    if (!Auth::user()->can('pdf nota dinas')) {
      return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mencetak PDF nota dinas.');
    }

    // cek apakah surat sudah disetujui kadis
    if ($surat->status !== 'disetujui_kadis') {
      return redirect()->back()->with('error', 'Surat tugas hanya dapat dicetak setelah disetujui oleh Kadis.');
    }

    try {
      return $this->suratService->generatePDFNotaDinas($surat);
    } catch (\Exception $e) {
      return redirect()->back()->with('alert', [
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Gagal mencetak PDF. Silakan coba lagi.',
      ]);
    }
  }

  /**
   * Cetak PDF Surat Tugas
   * 
   * @param \App\Models\SuratPerjalananDinas $surat
   * 
   * @return \Illuminate\Http\Response
   */
  public function cetakPDFSuratTugas(SuratPerjalananDinas $surat)
  {
    // cek apakah user memiliki permission 'pdf surat tugas'
    if (!Auth::user()->can('pdf surat tugas')) {
      return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mencetak PDF surat tugas.');
    }

    // cek apakah surat sudah disetujui kadis
    if ($surat->status !== 'disetujui_kadis') {
      return redirect()->back()->with('error', 'Surat tugas hanya dapat dicetak setelah disetujui oleh Kadis.');
    }

    try {
      return $this->suratService->generatePDFSuratTugas($surat);
    } catch (\Exception $e) {
      return redirect()->back()->with('alert', [
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Gagal mencetak PDF. Silakan coba lagi.',
      ]);
    }
  }
}
