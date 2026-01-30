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
        $stats = $this->dashboardService->getDashboardStats();
        return view('pages.dashboard', $stats);
    }

    /**
     * Statistik status pengajuan surat perjalanan dinas untuk chart.js
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusStatistics()
    {
        return response()->json($this->dashboardService->getStatusStatistics());
    }

    /**
     * Statistik proporsi penugasan pegawai berdasarkan golongan untuk chart.js
     * @return \Illuminate\Http\JsonResponse
     */
    public function golonganStatistics()
    {
        return response()->json($this->dashboardService->getGolonganStatistics());
    }

    /**
     * Data kalendar kegiatan dari surat perjalanan dinas yang disetujui
     * @return \Illuminate\Http\JsonResponse
     */
    public function calendarData()
    {
        return response()->json($this->dashboardService->getCalendarData());
    }
}
