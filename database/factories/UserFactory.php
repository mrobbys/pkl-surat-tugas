<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\PangkatGolongan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $namaDepan = [
            'Ahmad',
            'Muhammad',
            'Abdul',
            'Rizki',
            'Dian',
            'Siti',
            'Nur',
            'Dewi',
            'Andi',
            'Budi',
            'Cahya',
            'Deni',
            'Eko',
            'Fajar',
            'Gunawan',
            'Hendra',
            'Irwan',
            'Joko',
            'Kurnia',
            'Lukman',
            'Mira',
            'Nina',
            'Putri',
            'Ratna',
            'Surya',
            'Taufik',
            'Udin',
            'Vina',
            'Wahyu',
            'Yusuf',
            'Zainal',
            'Arif',
            'Bayu',
            'Citra',
            'Dinda',
            'Endang',
            'Fitri',
            'Galih',
            'Hesti',
            'Indra',
        ];

        $namaBelakang = [
            'Pratama',
            'Saputra',
            'Wijaya',
            'Kusuma',
            'Hidayat',
            'Rahman',
            'Siregar',
            'Harahap',
            'Nasution',
            'Lubis',
            'Hutabarat',
            'Simbolon',
            'Panjaitan',
            'Sari',
            'Wati',
            'Ningrum',
            'Lestari',
            'Rahayu',
            'Permana',
            'Putra',
            'Susanto',
            'Santoso',
            'Wibowo',
            'Nugroho',
            'Setiawan',
            'Hermawan',
            'Kurniawan',
            'Suryanto',
            'Prasetyo',
            'Hartono',
            'Sugiarto',
            'Budiman',
        ];

        $gelar = ['S.Kom', 'S.T', 'S.E', 'S.H', 'S.Sos', 'M.Kom', 'M.M', 'M.T', ''];

        $namaLengkap = fake()->randomElement($namaDepan) . ' ' . fake()->randomElement($namaBelakang);

        if (fake()->boolean(40)) {
            $namaLengkap .= ', ' . fake()->randomElement(array_filter($gelar));
        }

        $jabatan = [
            'Analis Kebijakan',
            'Pranata Komputer',
            'Pengelola Data',
            'Pengadministrasi Umum',
            'Pengelola Jaringan',
            'Analis Sistem Informasi',
            'Pengelola Website',
            'Operator Komputer',
            'Teknisi Jaringan',
            'Pengelola Layanan Informasi',
            'Penyusun Program',
            'Pengelola Basis Data',
            'Analis Data',
            'Pengelola Keamanan Informasi',
            'Pengelola Infrastruktur TI',
            'Staf Bidang Aplikasi',
            'Staf Bidang Informasi Publik',
            'Staf Bidang Statistik',
            'Staf Sekretariat',
            'Bendahara Pengeluaran',
        ];

        return [
            'nama_lengkap' => $namaLengkap,
            'nip' => Crypt::encryptString(fake()->unique()->numerify('##################')),
            'email' => Str::slug(strtolower($namaLengkap), '.') . '@gmail.com',
            'password' => static::$password ??= Hash::make('Password1.'),
            'pangkat_golongan_id' => PangkatGolongan::inRandomOrder()->first()?->id,
            'jabatan' => fake()->randomElement($jabatan),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
