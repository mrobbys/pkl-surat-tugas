<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PangkatGolongan;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // permissions untuk manajemen pangkat golongan
        Permission::create(['name' => 'view pangkat golongan', 'group' => 'Pangkat Golongan']);
        Permission::create(['name' => 'create pangkat golongan', 'group' => 'Pangkat Golongan']);
        Permission::create(['name' => 'edit pangkat golongan', 'group' => 'Pangkat Golongan']);
        Permission::create(['name' => 'delete pangkat golongan', 'group' => 'Pangkat Golongan']);

        // permissions untuk manajemen roles
        Permission::create(['name' => 'view roles', 'group' => 'Role']);
        Permission::create(['name' => 'create roles', 'group' => 'Role']);
        Permission::create(['name' => 'edit roles', 'group' => 'Role']);
        Permission::create(['name' => 'delete roles', 'group' => 'Role']);

        // permissions untuk manajemen users
        Permission::create(['name' => 'view users', 'group' => 'User']);
        Permission::create(['name' => 'create users', 'group' => 'User']);
        Permission::create(['name' => 'edit users', 'group' => 'User']);
        Permission::create(['name' => 'delete users', 'group' => 'User']);

        // permissions untuk activity log
        Permission::create(['name' => 'view activity log', 'group' => 'Activity Log']);

        // permissions untuk surat tugas
        Permission::create(['name' => 'view telaah staf', 'group' => 'Telaah Staf']);
        Permission::create(['name' => 'create telaah staf', 'group' => 'Telaah Staf']);
        Permission::create(['name' => 'edit telaah staf', 'group' => 'Telaah Staf']);
        Permission::create(['name' => 'delete telaah staf', 'group' => 'Telaah Staf']);

        // permissions untuk approval berjenjang telaah staf
        Permission::create(['name' => 'approve telaah staf level 1', 'group' => 'Approval Telaah Staf']);
        Permission::create(['name' => 'approve telaah staf level 2', 'group' => 'Approval Telaah Staf']);

        // permissions untuk pdf telaah staf dan surat tugas
        Permission::create(['name' => 'pdf telaah staf', 'group' => 'PDF']);
        Permission::create(['name' => 'pdf nota dinas', 'group' => 'PDF']);
        Permission::create(['name' => 'pdf surat tugas', 'group' => 'PDF']);

        // Buat roles superadmin
        $roleSuperAdmin = Role::create(['name' => 'super-admin']);
        // berikan semua permissions ke role superadmin
        $roleSuperAdmin->givePermissionTo(Permission::all());

        // Buat roles user
        $roleUser = Role::create(['name' => 'user']);
        // berikan permission view pangkat golongan ke role user
        $roleUser->givePermissionTo(['view pangkat golongan']);

        // Buat roles kasi
        $roleKasi = Role::create(['name' => 'kasi']);
        $roleKasi->givePermissionTo([
            'view telaah staf',
            'create telaah staf',
            'edit telaah staf',
            'delete telaah staf',
            'pdf telaah staf',
            'pdf nota dinas',
            'pdf surat tugas'
        ]);

        // Buat roles kabid
        $roleKabid = Role::create(['name' => 'kabid']);
        $roleKabid->givePermissionTo([
            'view telaah staf',
            'approve telaah staf level 1',
            'pdf telaah staf',
            'pdf nota dinas',
            'pdf surat tugas'
        ]);

        // Buat roles kadis
        $roleKadis = Role::create(['name' => 'kadis']);
        $roleKadis->givePermissionTo([
            'view telaah staf',
            'approve telaah staf level 2',
            'pdf telaah staf',
            'pdf nota dinas',
            'pdf surat tugas'
        ]);

        // Buat akun untuk superadmin
        $superAdmin = User::factory()->create([
            'nama_lengkap' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
        ]);
        // berikan role superadmin
        $superAdmin->assignRole('super-admin');

        // Buat akun untuk kasi
        $kasi = User::factory()->create([
            'nama_lengkap' => 'Kasi',
            'email' => 'kasi@gmail.com',
            'pangkat_golongan_id' => 2,
        ]);
        // berikan role kasi
        $kasi->assignRole('kasi');

        // Buat akun untuk kabid
        $kabid = User::factory()->create([
            'nama_lengkap' => 'Kabid',
            'email' => 'kabid@gmail.com',
            'pangkat_golongan_id' => 4,
        ]);
        // berikan role kabid
        $kabid->assignRole('kabid');

        // Buat akun untuk kadis
        $kadis = User::factory()->create([
            'nama_lengkap' => 'Kadis',
            'email' => 'kadis@gmail.com',
            'pangkat_golongan_id' => 5,
        ]);
        // berikan role kadis
        $kadis->assignRole('kadis');
    }
}
