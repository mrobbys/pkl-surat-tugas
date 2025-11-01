<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveTelaahStafRequest;
use Illuminate\Http\Request;
use App\Models\SuratPerjalananDinas;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTelaahStafRequest;
use App\Http\Requests\TerbitSuratTugasRequest;
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
      // ambil semua data surat via service
      $data = $this->suratService->getAllSurats();
      return response()->json(['data' => $data]);
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

    // validasi request
    $data = $request->validated();

    try {
      // simpan data telaah staf via service
      $this->suratService->storeTelaahStaf($data);

      // jika semua proses berhasil, kembalikan response success
      return response()->json([
        'status' => 'success',
        'icon' => 'success',
        'title' => 'Berhasil',
        'text' => 'Telaah Staf berhasil dibuat!',
      ], 201);
    } catch (\Exception $e) {
      Log::error('Error storing telaah staf: ' . $e->getMessage());
      // jika terjadi error, kembalikan response error
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Telaah Staf gagal dibuat! ' . $e->getMessage(),
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
    $data = $this->suratService->getTelaahStafDetail($surat);
    return view('pages.surat.form-telaah-staf', [
      'mode' => 'show',
      'pegawais' => $data['pegawais'],
      'surat' => $surat,
      'data' => $data,
    ]);
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
    $data = $this->suratService->getTelaahStafForEdit($surat);
    $pegawais = $this->suratService->getPegawaisForSelect();

    return view('pages.surat.form-telaah-staf', [
      'mode' => 'edit',
      'pegawais' => $pegawais,
      'surat' => $surat,
      'data' => $data,
    ]);
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

    // validasi request
    $data = $request->validated();

    try {
      // update data telaah staf via service
      $this->suratService->updateTelaahStaf($surat, $data);

      // jika semua proses berhasil, kembalikan response success
      return response()->json([
        'status' => 'success',
        'icon' => 'success',
        'title' => 'Berhasil',
        'text' => 'Telaah Staf berhasil diupdate!',
      ], 200);
    } catch (\Exception $e) {
      Log::error('Error updating telaah staf: ' . $e->getMessage());
      // jika terjadi error, kembalikan response error
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Telaah Staf gagal diupdate! ' . $e->getMessage(),
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

    try {
      // hapus telaah staf via service
      $this->suratService->destroyTelaahStaf($surat);

      return response()->json([
        'status' => 'success',
        'icon' => 'success',
        'title' => 'Berhasil',
        'text' => 'Telaah Staf berhasil dihapus!',
      ], 200);
    } catch (\Exception $e) {
      Log::error('Error deleting telaah staf: ' . $e->getMessage());
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Telaah Staf gagal dihapus! ' . $e->getMessage(),
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

    // validasi request
    $data = $request->validated();

    try {
      // approve telaah staf level 1 via service
      $statusPenyetujuSatu = $this->suratService->approveSatuTelaahStaf($surat, $data);

      // kembalikan response success
      return response()->json([
        'status' => 'success',
        'icon' => 'success',
        'title' => 'Berhasil',
        'text' => "Telaah Staf berhasil {$statusPenyetujuSatu}!",
      ], 200);
    } catch (\Exception $e) {
      Log::error('Error approving telaah staf level 1: ' . $e->getMessage());
      // kembalikan response error
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Telaah Staf gagal disetujui! ' . $e->getMessage(),
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

    // validasi request
    $data = $request->validated();

    try {
      // approve telaah staf level 2 via service
      $statusPenyetujuDua = $this->suratService->approveDuaTelaahStaf($surat, $data);

      return response()->json([
        'status' => 'success',
        'icon' => 'success',
        'title' => 'Berhasil',
        'text' => "Telaah Staf berhasil {$statusPenyetujuDua}!",
      ], 200);
    } catch (\Exception $e) {
      Log::error('Error approving telaah staf level 2: ' . $e->getMessage());
      return response()->json([
        'status' => 'error',
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Telaah Staf gagal disetujui! ' . $e->getMessage(),
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
      // generate PDF telaah staf via service
      return $this->suratService->generatePDFTelaahStaf($surat);
    } catch (\Exception $e) {
      Log::error('Error generating PDF for telaah staf: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Gagal mencetak PDF: ' . $e->getMessage());
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
    if (!Auth::user()->can('pdf nota dinas')){
      return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mencetak PDF nota dinas.');
    }

    // cek apakah surat sudah disetujui kadis
    if ($surat->status !== 'disetujui_kadis') {
      return redirect()->back()->with('error', 'Surat tugas hanya dapat dicetak setelah disetujui oleh Kadis.');
    }

    try {
      // generate PDF nota dinas via service
      return $this->suratService->generatePDFNotaDinas($surat);
    } catch (\Exception $e) {
      Log::error('Error generating PDF for nota dinas: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Gagal mencetak PDF: ' . $e->getMessage());
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
      // generate PDF surat tugas via service
      return $this->suratService->generatePDFSuratTugas($surat);
    } catch (\Exception $e) {
      Log::error('Error generating PDF for surat tugas: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Gagal mencetak PDF: ' . $e->getMessage());
    }
  }
}
