<?php

namespace App\Http\Controllers;

use App\Models\SuratPerjalananDinas;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        // count total pegawai (kecuali superadmin)
        $totalPegawai = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->count();
        // count total roles
        $totalRoles = Role::count();
        // count total surat dibuat
        $totalSurat = SuratPerjalananDinas::count();
        // 5 surat perjalanan dinas terakhir yang baru dibuat
        $recentSurat = SuratPerjalananDinas::select('id', 'nomor_telaahan', 'tanggal_telaahan', 'status', 'pembuat_id')
            ->with('pembuat:id,nama_lengkap')
            ->latest()
            ->take(5)
            ->get();

        return view('pages.dashboard', [
            'totalPegawai' => $totalPegawai,
            'totalRoles' => $totalRoles,
            'totalSurat' => $totalSurat,
            'recentSurat' => $recentSurat,
        ]);
    }

    public function calendarData()
    {
        $kegiatan = [];

        // ambil data surat perjalanan dinas yang disetujui
        $suratPerjalananDinas = SuratPerjalananDinas::select('perihal_kegiatan', 'tanggal_mulai', 'tanggal_selesai')
            ->whereIn('status', ['disetujui_kabid', 'disetujui_kadis'])
            ->get();

        foreach ($suratPerjalananDinas as $surat) {
            $kegiatan[] = [
                'title' => $surat->perihal_kegiatan,
                'start' => $surat->tanggal_mulai,
                'end' => $surat->tanggal_selesai,
            ];
        }

        return response()->json($kegiatan);
    }
}
