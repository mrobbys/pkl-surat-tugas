<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    // service user
    protected $userService;
    
    // inject service via constructor
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    /**
     * Tampil semua data users
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Json\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // jika request ajax, maka kembalikan data json untuk datatables
        if ($request->ajax()) {
            // ambil data users kecuali superadmin
            $data = $this->userService->getAllUsers();

            return response()->json(['data' => $data]);
        }
        return view('pages.users.index');
    }

    /**
     * Create user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $data = $this->userService->createUser();
        return response()->json(['data' => $data]);
    }

    /**
     * Simpan data user
     * 
     * @param \App\Http\Requests\StoreUserRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        // cek apakah user memiliki permission 'create users'
        if (!Auth::user()->can('create users')) {
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Anda tidak memiliki izin untuk membuat user.',
            ], 403);
        }
        
        // validasi request
        $data = $request->validated();

        try {
            $this->userService->storeUser($data);

            // kembalikan response json berhasil
            return response()->json([
                'status' => 'success',
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'User berhasil ditambahkan!',
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating User: ' . $e->getMessage());
            // jika error, kembalikan response json gagal
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'User gagal ditambahkan! ' . $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Show user
     * 
     * @param \App\Models\User $user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        $data = $this->userService->getUser($user);
        return response()->json(['data' => $data]);
    }

    /**
     * Edit user
     * 
     * @param \App\Models\User $user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(User $user)
    {
        $data = $this->userService->getUser($user);
        return response()->json(['data' => $data]);
    }

    /**
     * Update data user
     * 
     * @param \App\Http\Requests\UpdateUserRequest $request
     * @param \App\Models\User $user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // cek apakah user memiliki permission 'edit users'
        if (!Auth::user()->can('edit users')) {
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Anda tidak memiliki izin untuk mengedit user.',
            ], 403);
        }

        // validasi request
        $data = $request->validated();

        try {
            $this->userService->updateUser($user, $data);

            // kembalikan response json berhasil
            return response()->json([
                'status' => 'success',
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'User berhasil diupdate!',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating User: ' . $e->getMessage());
            // jika error, kembalikan response json gagal
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'User gagal diupdate! ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus data user
     * 
     * @param \App\Models\User $user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        // cek apakah user memiliki permission 'delete users'
        if (!Auth::user()->can('delete users')) {
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Anda tidak memiliki izin untuk menghapus user.',
            ], 403);
        }

        try {
            $this->userService->deleteUser($user);
            return response()->json([
                'status' => 'success',
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'User berhasil dihapus!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'User gagal dihapus! ' . $e->getMessage(),
            ], 500);
        }
    }
}
