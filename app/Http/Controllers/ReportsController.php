<?php

namespace App\Http\Controllers;

use App\Services\ReportsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
  protected $reportsService;

  public function __construct(ReportsService $reportsService)
  {
    $this->reportsService = $reportsService;
  }

  /**
   * Validasi rentang tanggal
   * @param Request $request
   * 
   * @return array
   */
  private function validateDateRange(Request $request): array
  {
    return $request->validate([
      'tanggal_awal' => 'required|date|before_or_equal:today',
      'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal|before_or_equal:today',
    ], [
      'tanggal_awal.required' => 'Tanggal awal wajib diisi.',
      'tanggal_awal.date' => 'Tanggal awal harus berupa tanggal yang valid.',
      'tanggal_awal.before_or_equal' => 'Tanggal awal tidak boleh lebih dari hari ini.',

      'tanggal_akhir.required' => 'Tanggal akhir wajib diisi.',
      'tanggal_akhir.date' => 'Tanggal akhir harus berupa tanggal yang valid.',
      'tanggal_akhir.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal awal.',
      'tanggal_akhir.before_or_equal' => 'Tanggal akhir tidak boleh lebih dari ini.'
    ]);
  }

  /**
   * Menampilkan halaman index laporan  
   * @return \Illuminate\View\View
   */
  public function index()
  {
    return view('pages.reports.index');
  }

  /**
   * Rekapitulasi Surat Telaahan Staf
   * @param Request $request
   * 
   * @return \Illuminate\Http\Response
   */
  public function rekapitulasiSuratTelaahanStaf(Request $request)
  {
    try {
      $validatedData = $this->validateDateRange($request);
      return $this->reportsService->generateRekapitulasiSuratTelaahanStaf($validatedData);
    } catch (\Exception $e) {
      return back()->with('alert', [
        'icon' => 'error',
        'title' => 'Error',
        'text' => 'Gagal generate laporan.',
      ]);
    }
  }

  /**
   * Rekapitulasi Surat Nota Dinas
   * @param Request $request
   * 
   * @return \Illuminate\Http\Response
   */
  public function rekapitulasiSuratNotaDinas(Request $request)
  {
    try {
      $validatedData = $this->validateDateRange($request);
      return $this->reportsService->generateRekapitulasiSuratNotaDinas($validatedData);
    } catch (\Exception $e) {
      return back()->with('alert', [
        'icon' => 'error',
        'title' => 'Error',
        'text' => 'Gagal generate laporan.',
      ]);
    }
  }

  /**
   * Rekapitulasi Surat Tugas
   * @param Request $request
   * 
   * @return \Illuminate\Http\Response
   */
  public function rekapitulasiSuratTugas(Request $request)
  {
    try {
      $validatedData = $this->validateDateRange($request);
      // Implementasi generateRekapitulasiSuratTugas di ReportsService
      return $this->reportsService->generateRekapitulasiSuratTugas($validatedData);
    } catch (\Exception $e) {
      return back()->with('alert', [
        'icon' => 'error',
        'title' => 'Error',
        'text' => 'Gagal generate laporan.',
      ]);
    }
  }

  /**
   * Rekapitulasi Aktivitas Pegawai
   * @param Request $request
   * 
   * @return \Illuminate\Http\Response
   */
  public function rekapitulasiAktivitasPegawai(Request $request)
  {
    try {
      $validatedData = $this->validateDateRange($request);
      return $this->reportsService->generateRekapitulasiAktivitasPegawai($validatedData);
    } catch (\Exception $e) {
      return back()->with('alert', [
        'icon' => 'error',
        'title' => 'Error',
        'text' => 'Gagal generate laporan.',
      ]);
    }
  }

  /**
   * Master Pegawai
   * @param Request $request
   * 
   * @return \Illuminate\Http\Response
   */
  public function masterPegawai(Request $request)
  {
    try {
      $validatedData = $this->validateDateRange($request);
      return $this->reportsService->generateMasterPegawai($validatedData);
    } catch (\Exception $e) {
      return back()->with('alert', [
        'icon' => 'error',
        'title' => 'Error',
        'text' => 'Gagal generate laporan.',
      ]);
    }
  }
}
