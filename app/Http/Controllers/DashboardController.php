<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\SuratPerjalananDinas;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Menampilkan statistik dan data surat terbaru di dashboard
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $stats = $this->dashboardService->getDashboardStats();
            return view('pages.dashboard', $stats); //code...
        } catch (\Exception $e) {
            return view('pages.dashboard', [
                'totalPegawai' => 0,
                'totalRoles' => 0,
                'totalSurat' => 0,
                'recentSurat' => collect([]),
                'recentActivities' => collect([]),
                'currentYear' => date('Y'),
            ])->with('error', 'Gagal memuat data dashboard :(');
        }
    }

    /**
     * Statistik intensitas penugasan pegawai untuk chart.js
     * @return \Illuminate\Http\JsonResponse
     */
    public function intensityStatistics()
    {
        try {
            $data = $this->dashboardService->getIntensityStatistics();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'year' => date('Y'),
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'data' => array_fill(0, 12, 0),
            ], 500);
        }
    }

    /**
     * Statistik status pengajuan surat perjalanan dinas untuk chart.js
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusStatistics()
    {
        try {
            $data = $this->dashboardService->getStatusStatistics();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'year' => date('Y'),
                'labels' => ['Dalam Proses', 'Revisi', 'Ditolak', 'Selesai / Terbit'],
                'data' => [0, 0, 0, 0],
            ], 500);
        }
    }

    /**
     * Statistik proporsi penugasan pegawai berdasarkan golongan untuk chart.js
     * @return \Illuminate\Http\JsonResponse
     */
    public function golonganStatistics()
    {
        try {
            $data = $this->dashboardService->getGolonganStatistics();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'year' => date('Y'),
                'labels' => ['Tidak Ada Data'],
                'data' => [0],
            ], 500);
        }
    }

    /**
     * Data kalendar kegiatan dari surat perjalanan dinas yang disetujui
     * @return \Illuminate\Http\JsonResponse
     */
    public function calendarData()
    {
        try {
            $data = $this->dashboardService->getCalendarData();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([], 500);
        }
    }
}
