<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreLoginRequest;

class LoginController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return view('pages.auth.login');
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(StoreLoginRequest $request)
    {
        // Validasi dan ambil data dari request
        $credentials = $request->validated();
        // Cek remember me
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // untuk bagian text nanti tampilkan berdasarkan nama pegawai yang diambil dari relasi table pegawais
            return redirect()->route('dashboard')->with('alert', [
                'icon' => 'success',
                'title' => 'Login Berhasil!',
                'text' => 'Selamat datang, ' . Auth::user()->nama_lengkap . '!',
            ]);
        }
        
        // Jika autentikasi gagal, kembalikan ke halaman login dengan pesan error
        return redirect()->back()->with('alert', [
            'icon' => 'error',
            'title' => 'Login Gagal!',
            'text' => 'Email atau password salah. Silakan coba lagi.',
        ])->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        // cari user yang sedang login
        $user = Auth::user();

        // jika ada user yang sedang login, lakukan proses logout
        if ($user) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        }

        // jika tidak ada user yang sedang login, kembalikan ke halaman login dengan pesan peringatan
        return redirect()->back()->with('alert', [
            'icon' => 'warning',
            'title' => 'Logout Gagal!',
            'text' => 'Tidak ada pengguna yang sedang login.',
        ]);
    }
}
