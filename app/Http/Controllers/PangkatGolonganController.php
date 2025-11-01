<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PangkatGolongan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\PangkatGolonganService;
use App\Http\Requests\StorePangkatGolonganRequest;
use App\Http\Requests\UpdatePangkatGolonganRequest;

class PangkatGolonganController extends Controller
{
    // service pangkat golongan
    protected $pangkatGolonganService;

    // inject service via constructor
    public function __construct(PangkatGolonganService $pangkatGolonganService)
    {
        $this->pangkatGolonganService = $pangkatGolonganService;
    }

    /**
     * Tampil data pangkat golongan
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // ambil semua data pangkat golongan via service
            $data = $this->pangkatGolonganService->getAllPangkatGolongans();
            return response()->json(['data' => $data]);
        }
        return view('pages.pangkat-golongan.index');
    }

    /**
     * Simpan data pangkat golongan
     * 
     * @param \App\Http\Requests\StorePangkatGolonganRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePangkatGolonganRequest $request)
    {
        // cek apakah user memiliki permission 'create pangkat golongan'
        if (!Auth::user()->can('create pangkat golongan')) {
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Anda tidak memiliki izin untuk membuat pangkat golongan.',
            ], 403);
        }

        try {
            // validasi request di StorePangkatGolonganRequest
            $data = $request->validated();
            // simpan data pangkat golongan via service
            $this->pangkatGolonganService->storePangkatGolongan($data);

            // kembalikan response json jika berhasil
            return response()->json([
                'status' => 'success',
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Pangkat dan Golongan berhasil ditambahkan!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating PangkatGolongan: ' . $e->getMessage());
            // kembalikan response json jika error
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal menambahkan Pangkat dan Golongan.' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ambil data pangkat golongan untuk edit
     * 
     * @param \App\Models\PangkatGolongan $pangkatGolongan
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(PangkatGolongan $pangkatGolongan)
    {
        // ambil data pangkat golongan via service
        $pg = $this->pangkatGolonganService->getPangkatGolonganForEdit($pangkatGolongan);
        return response()->json([
            'data' => $pg
        ]);
    }

    /**
     * Update data pangkat golongan
     * 
     * @param \App\Http\Requests\UpdatePangkatGolonganRequest $request
     * @param \App\Models\PangkatGolongan $pangkatGolongan
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePangkatGolonganRequest $request, PangkatGolongan $pangkatGolongan)
    {
        // cek apakah user memiliki permission 'edit pangkat golongan'
        if (!Auth::user()->can('edit pangkat golongan')) {
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Anda tidak memiliki izin untuk mengedit pangkat golongan.',
            ], 403);
        }

        try {
            // validasi request di UpdatePangkatGolonganRequest
            $data = $request->validated();
            // update data pangkat golongan via service
            $this->pangkatGolonganService->updatePangkatGolongan($pangkatGolongan, $data);

            // kembalikan response json jika berhasil
            return response()->json([
                'status' => 'success',
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Pangkat dan Golongan berhasil diperbarui!',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating PangkatGolongan: ' . $e->getMessage());
            // kembalikan response json jika error
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal memperbarui Pangkat dan Golongan. ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus data pangkat golongan
     * 
     * @param \App\Models\PangkatGolongan $pangkatGolongan
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PangkatGolongan $pangkatGolongan)
    {
        // cek apakah user memiliki permission 'delete pangkat golongan'
        if (!Auth::user()->can('delete pangkat golongan')) {
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Anda tidak memiliki izin untuk menghapus pangkat golongan.',
            ], 403);
        }

        try {
            // hapus pangkat golongan via service
            $this->pangkatGolonganService->destroyPangkatGolongan($pangkatGolongan);

            return response()->json([
                'status' => 'success',
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Pangkat dan Golongan berhasil dihapus!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting PangkatGolongan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal menghapus Pangkat dan Golongan. ' . $e->getMessage(),
            ], 500);
        }
    }
}
