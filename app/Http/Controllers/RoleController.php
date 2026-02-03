<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{
    // service role
    protected $roleService;

    // inject service via constructor
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Tampil data roles
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = $this->roleService->getAllRoles();
                return response()->json(['data' => $data]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengambil data roles.'
                ], 500);
            }
        }
        return view('pages.roles.index');
    }

    /**
     * Ambil data permissions untuk create role
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        try {
            $data = $this->roleService->getPermissionsList();
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data permissions.'
            ], 500);
        }
    }

    /**
     * Simpan data role
     * 
     * @param \App\Http\Requests\StoreRoleRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRoleRequest $request)
    {
        // cek apakah user memiliki permission 'create roles'
        if (!Auth::user()->can('create roles')) {
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Anda tidak memiliki izin untuk membuat role.',
            ], 403);
        };

        try {
            // simpan data role via service
            $this->roleService->storeRole($request->validated());

            // kembalikan response success
            return response()->json([
                'status' => 'success',
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Role berhasil ditambahkan!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal menambahkan role. Silahkan coba lagi.',
            ], 500);
        }
    }

    /**
     * Role detail
     * 
     * @param \Spatie\Permission\Models\Role $role
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        try {
            $data = $this->roleService->getRoleDetail($role);
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail role.'
            ], 500);
        }
    }

    /**
     * Edit role
     * 
     * @param \Spatie\Permission\Models\Role $role
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Role $role)
    {
        try {
            $data = $this->roleService->getRoleForEdit($role);
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data role untuk diedit.'
            ], 500);
        }
    }

    /**
     * Update data role
     * 
     * @param \App\Http\Requests\UpdateRoleRequest $request
     * @param \Spatie\Permission\Models\Role $role
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        // cek apakah user memiliki permission 'edit roles'
        if (!Auth::user()->can('edit roles')) {
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Anda tidak memiliki izin untuk mengedit role.',
            ], 403);
        }

        try {
            // simpan data role via service
            $this->roleService->updateRole($role, $request->validated());

            return response()->json([
                'status' => 'success',
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Role berhasil diupdate!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal mengupdate role. Silahkan coba lagi.',
            ], 500);
        }
    }

    /**
     * Hapus data role
     * 
     * @param \Spatie\Permission\Models\Role $role
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        // cek apakah user memiliki permission 'delete roles'
        if (!Auth::user()->can('delete roles')) {
            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Anda tidak memiliki izin untuk menghapus role.',
            ], 403);
        }

        try {
            // hapus data role via service
            $this->roleService->destroyRole($role);

            return response()->json([
                'status' => 'success',
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Role berhasil dihapus!'
            ], 200);
        } catch (\Exception $e) {
            // cek apakah error karena role masih digunakan
            $errorMessage = str_contains($e->getMessage(), 'masih digunakan')
                ? $e->getMessage()
                : 'Role gagal dihapus. Silakan coba lagi.';

            return response()->json([
                'status' => 'error',
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => $errorMessage,
            ], 422);
        }
    }
}
