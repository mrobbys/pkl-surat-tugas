<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SuratPerjalananDinas>
 */
class SuratPerjalananDinasFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    // Data untuk Kepada Yth
    $kepadaYth = [
      'Walikota Banjarbaru',
      'Sekretaris Daerah Kota Banjarbaru',
      'Asisten Pemerintahan dan Kesejahteraan Rakyat',
      'Kepala Badan Kepegawaian dan Pengembangan SDM',
      'Kepala Badan Perencanaan Pembangunan Daerah',
      'Inspektur Kota Banjarbaru',
      'Kepala Dinas Komunikasi dan Informatika Provinsi Kalsel',
      'Pejabat Pembuat Komitmen',
    ];

    // Data untuk Dari
    $dari = [
      'Kepala Dinas Komunikasi dan Informatika',
      'Sekretaris Dinas Komunikasi dan Informatika',
      'Kepala Bidang Aplikasi dan Informatika',
      'Kepala Bidang Informasi dan Komunikasi Publik',
      'Kepala Bidang Statistik dan Persandian',
    ];

    // Data untuk Perihal Kegiatan
    $perihalKegiatan = [
      'Permohonan Izin Mengikuti Kegiatan Bimbingan Teknis Pengelolaan Data Center',
      'Permohonan Izin Mengikuti Workshop Implementasi Smart City',
      'Permohonan Izin Mengikuti Rapat Koordinasi SPBE Tingkat Provinsi',
      'Permohonan Izin Melaksanakan Kunjungan Kerja Studi Banding SPBE',
      'Permohonan Izin Mengikuti Sosialisasi Keamanan Informasi dan Persandian',
      'Permohonan Izin Mengikuti Pelatihan Pengelolaan Website dan Media Sosial',
      'Permohonan Izin Mengikuti FGD Pengembangan Aplikasi Pelayanan Publik',
      'Permohonan Izin Mengikuti Seminar Nasional Transformasi Digital',
      'Permohonan Izin Melaksanakan Monitoring dan Evaluasi Infrastruktur TIK',
      'Permohonan Izin Mengikuti Rapat Kerja Teknis Bidang Statistik Sektoral',
      'Permohonan Izin Mengikuti Bimtek Sistem Keamanan Jaringan',
      'Permohonan Izin Melaksanakan Koordinasi Pengembangan E-Government',
      'Permohonan Izin Mengikuti Pelatihan Manajemen Layanan TI',
      'Permohonan Izin Mengikuti Workshop Integrasi Data dan Interoperabilitas',
      'Permohonan Izin Melaksanakan Asistensi Teknis Aplikasi Pemerintahan',
    ];

    // Data untuk Tempat Pelaksanaan
    $tempatPelaksanaan = [
      'Aula Dinas Komunikasi dan Informatika Provinsi Kalimantan Selatan',
      'Hotel Rodhita Banjarbaru',
      'Gedung Balaikota Banjarmasin',
      'Ruang Rapat Bappeda Provinsi Kalimantan Selatan',
      'Aula BPSDM Provinsi Kalimantan Selatan',
      'Hotel Golden Tulip Banjarmasin',
      'Gedung DPRD Provinsi Kalimantan Selatan',
      'Ruang Pertemuan Dinas Kominfo Kota Banjarmasin',
      'Convention Hall Novotel Banjarmasin',
      'Aula Kantor Gubernur Kalimantan Selatan',
      'Hotel Aston Banjarmasin',
      'Ruang Rapat BPS Provinsi Kalimantan Selatan',
      'Gedung Serbaguna Pemkot Banjarbaru',
      'Aula KPID Kalimantan Selatan',
      'Ruang Pelatihan BKPSDM Kota Banjarbaru',
    ];

    // Data untuk Dasar Telaahan
    $dasarTelaahan = [
      'Dalam rangka meningkatkan kompetensi aparatur di bidang teknologi informasi dan komunikasi serta mendukung penyelenggaraan pemerintahan berbasis elektronik',
      'Berdasarkan Peraturan Presiden Nomor 95 Tahun 2018 tentang Sistem Pemerintahan Berbasis Elektronik dan dalam rangka meningkatkan nilai indeks SPBE',
      'Dalam rangka pelaksanaan tugas dan fungsi Dinas Komunikasi dan Informatika serta mendukung program transformasi digital pemerintahan',
      'Berdasarkan undangan dari Kementerian Komunikasi dan Informatika RI perihal pelaksanaan kegiatan peningkatan kapasitas SDM bidang TIK',
      'Dalam rangka koordinasi dan sinkronisasi program kerja bidang komunikasi dan informatika dengan instansi terkait di tingkat provinsi',
      'Berdasarkan Peraturan Menteri PAN-RB tentang Pedoman Evaluasi SPBE dan dalam rangka persiapan evaluasi tahun berjalan',
      'Dalam rangka mendukung pencapaian target kinerja Dinas Komunikasi dan Informatika sesuai dengan Rencana Strategis yang telah ditetapkan',
      'Berdasarkan hasil rapat koordinasi internal dan dalam rangka penyelarasan program kerja dengan kebijakan pemerintah pusat',
    ];

    // Isi Telaahan (HTML format)
    $isiTelaahan = '<ol>
            <li>Salah satu cara untuk mewujudkan tata kelola pemerintahan yang efektif dan efisien adalah dengan dibentuknya sistem pemerintahan berbasis elektronik (SPBE). SPBE merupakan suatu sistem tata kelola pemerintah yang memanfaatkan teknologi informasi secara menyeluruh dan terpadu dalam pelaksanaan administrasi pemerintahan dan penyelenggaraan pelayanan publik yang dilakukan pada suatu instansi pemerintahan.</li>
            <li>Di informasikan bahwa berdasarkan hasil Pemantauan dan evaluasi SPBE tahun 2026 yang telah ditetapkan dengan Keputusan Kementrian PAN dan RB RI Pemerintah Kota Banjarbaru masih memperoleh indeks SPBE dengan nilai 2,68 kategori Baik, sehingga dipandang perlu kita melakukan studi tiru dan komparasi atas hasil nilai SPBE yang diperoleh dengan daerah lainnya untuk meningkatkan nilai indeks SPBE yang ada.</li>
            <li>Kunjungan kerja untuk melakukan konsultasi dan studi banding dalam rangka meningkatkan nilai indeks SPBE untuk mendapatkan wawasan terkait Program kerja DISKOMINFOTIK Kota Banjarmasin dalam Penyelenggaraan SPBE terutama yang berkaitan dengan tatakelola SPBE yaitu manajemen SPBE dan Audit TIK.</li>
            <li>Kegiatan ini dilaksanakan sesuai dengan jadwal yang telah ditentukan bertempat di lokasi kegiatan yang bersangkutan.</li>
            <li>Perjalanan Dinas mengikuti kegiatan dimaksud melalui jalur darat dengan keberangkatan dan kepulangan sesuai jadwal pelaksanaan kegiatan.</li>
            <li>Biaya perjalanan dinas dibebankan pada DPA Dinas Komunikasi dan Informatika Kota Banjarbaru Tahun Anggaran 2026.</li>
        </ol>';

    // Generate tanggal telaahan (1 Jan - 4 Feb 2026)
    $tanggalTelaahan = fake()->dateTimeBetween('2026-01-01', '2026-02-04');

    // Generate tanggal mulai (setelah atau sama dengan tanggal telaahan)
    $tanggalMulai = fake()->dateTimeBetween($tanggalTelaahan, '2026-02-04');

    // Generate tanggal selesai (setelah atau sama dengan tanggal mulai)
    $maxTanggalSelesai = min(
      strtotime('+3 days', $tanggalMulai->getTimestamp()),
      strtotime('2026-02-04')
    );
    $tanggalSelesai = fake()->dateTimeBetween($tanggalMulai, date('Y-m-d', $maxTanggalSelesai));

    // Generate nomor telaahan dengan format: XXX/YYY/Info/DISKOMINFO/2026
    $nomorTelaahan = sprintf(
      '%03d/%03d/Info/DISKOMINFO/2026',
      fake()->numberBetween(1, 999),
      fake()->numberBetween(1, 999)
    );

    return [
      'kepada_yth' => fake()->randomElement($kepadaYth),
      'dari' => fake()->randomElement($dari),
      'nomor_telaahan' => $nomorTelaahan,
      'tanggal_telaahan' => $tanggalTelaahan->format('Y-m-d'),
      'perihal_kegiatan' => fake()->randomElement($perihalKegiatan),
      'tempat_pelaksanaan' => fake()->randomElement($tempatPelaksanaan),
      'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
      'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
      'dasar_telaahan' => fake()->randomElement($dasarTelaahan),
      'isi_telaahan' => $isiTelaahan,
      'pembuat_id' => User::role('kasi')->inRandomOrder()->first()?->id ?? 1,
      'status' => 'diajukan',
      'created_at' => $tanggalTelaahan,
      'updated_at' => $tanggalTelaahan,
    ];
  }

  /**
   * Status: Disetujui Kabid (Level 1 Approved)
   */
  public function disetujuiKabid(): static
  {
    return $this->state(function (array $attributes) {
      $kabid = User::role('kabid')->first();
      $tanggalStatusSatu = fake()->dateTimeBetween($attributes['tanggal_telaahan'], '2026-02-04');

      return [
        'penyetuju_satu_id' => $kabid?->id,
        'status_penyetuju_satu' => 'disetujui',
        'tanggal_status_satu' => $tanggalStatusSatu,
        'catatan_satu' => fake()->randomElement([
          'Disetujui, silakan lanjutkan proses.',
          'Setuju untuk dilaksanakan.',
          'Acc, mohon koordinasi dengan bidang terkait.',
          null,
        ]),
        'status' => 'disetujui_kabid',
        'updated_at' => $tanggalStatusSatu,
      ];
    });
  }

  /**
   * Status: Disetujui Kadis (Level 2 Approved / Final)
   */
  public function disetujuiKadis(): static
  {
    return $this->disetujuiKabid()->state(function (array $attributes) {
      $kadis = User::role('kadis')->first();
      $tanggalStatusDua = fake()->dateTimeBetween($attributes['tanggal_status_satu'] ?? $attributes['tanggal_telaahan'], '2026-02-04');

      // generate nomor surat tugas and nota dinas
      // contoh : 0001/2026
      $numbers = fake()->unique()->numberBetween(1, 9999);
      $generateNumber = sprintf('%04d/2026', $numbers);

      return [
        'penyetuju_dua_id' => $kadis?->id,
        'status_penyetuju_dua' => 'disetujui',
        'tanggal_status_dua' => $tanggalStatusDua,
        'catatan_dua' => fake()->randomElement([
          'Disetujui.',
          'Setuju, laksanakan dengan baik.',
          'Acc.',
          null,
        ]),
        'nomor_surat_tugas' => $generateNumber,
        'nomor_nota_dinas' => $generateNumber,
        'status' => 'disetujui_kadis',
        'updated_at' => $tanggalStatusDua,
      ];
    });
  }

  /**
   * Status: Revisi Kadis
   */
  public function revisiKadis(): static
  {
    return $this->disetujuiKabid()->state(function (array $attributes) {
      $kadis = User::role('kadis')->first();
      $tanggalStatusDua = fake()->dateTimeBetween($attributes['tanggal_status_satu'] ?? $attributes['tanggal_telaahan'], '2026-02-04');

      return [
        'penyetuju_dua_id' => $kadis?->id,
        'status_penyetuju_dua' => 'revisi',
        'tanggal_status_dua' => $tanggalStatusDua,
        'catatan_dua' => fake()->randomElement([
          'Mohon perbaiki jadwal pelaksanaan.',
          'Anggaran perlu disesuaikan.',
          'Tambahkan informasi peserta yang ditugaskan.',
        ]),
        'status' => 'revisi_kadis',
        'updated_at' => $tanggalStatusDua,
      ];
    });
  }

  /**
   * Status: Revisi Kabid
   */
  public function revisiKabid(): static
  {
    return $this->state(function (array $attributes) {
      $kabid = User::role('kabid')->first();
      $tanggalStatusSatu = fake()->dateTimeBetween($attributes['tanggal_telaahan'], '2026-02-04');

      return [
        'penyetuju_satu_id' => $kabid?->id,
        'status_penyetuju_satu' => 'revisi',
        'tanggal_status_satu' => $tanggalStatusSatu,
        'catatan_satu' => fake()->randomElement([
          'Mohon perbaiki format nomor surat.',
          'Tanggal pelaksanaan perlu disesuaikan.',
          'Tambahkan detail peserta yang ditugaskan.',
          'Perihal kegiatan kurang jelas, mohon diperbaiki.',
        ]),
        'status' => 'revisi_kabid',
        'updated_at' => $tanggalStatusSatu,
      ];
    });
  }

  /**
   * Status: Ditolak Kadis
   */
  public function ditolakKadis(): static
  {
    return $this->disetujuiKabid()->state(function (array $attributes) {
      $kadis = User::role('kadis')->first();
      $tanggalStatusDua = fake()->dateTimeBetween($attributes['tanggal_status_satu'] ?? $attributes['tanggal_telaahan'], '2026-02-04');

      return [
        'penyetuju_satu_id' => $kadis?->id,
        'status_penyetuju_satu' => 'ditolak',
        'tanggal_status_satu' => $tanggalStatusDua,
        'catatan_satu' => fake()->randomElement([
          'Kegiatan tidak relevan dengan program kerja.',
          'Anggaran tidak mencukupi.',
          'Nama Pejabat ada typo.',
        ]),
        'status' => 'ditolak_kadis',
        'updated_at' => $tanggalStatusDua,
      ];
    });
  }

  /**
   * Status: Ditolak Kabid
   */
  public function ditolakKabid(): static
  {
    return $this->state(function (array $attributes) {
      $kabid = User::role('kabid')->first();
      $tanggalStatusSatu = fake()->dateTimeBetween($attributes['tanggal_telaahan'], '2026-02-04');

      return [
        'penyetuju_satu_id' => $kabid?->id,
        'status_penyetuju_satu' => 'ditolak',
        'tanggal_status_satu' => $tanggalStatusSatu,
        'catatan_satu' => fake()->randomElement([
          'Kegiatan tidak sesuai dengan program kerja.',
          'Anggaran tidak mencukupi.',
          'Jadwal bentrok dengan kegiatan prioritas lain.',
        ]),
        'status' => 'ditolak_kabid',
        'updated_at' => $tanggalStatusSatu,
      ];
    });
  }
}
