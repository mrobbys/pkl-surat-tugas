<?php

namespace App\Services;

use App\Models\PangkatGolongan;

class PangkatGolonganService
{
  /**
   * Ambil semua data pangkat golongan untuk datatables
   * 
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getAllPangkatGolongans()
  {
    // ambil data pangkat golongan dan urutkan berdasarkan id asc
    return PangkatGolongan::orderBy('id', 'desc')->get();
  }

  /**
   * Simpan data pangkat golongan
   * 
   * @param array $data
   * 
   * @return \App\Models\PangkatGolongan
   */
  public function storePangkatGolongan(array $data)
  {
    return PangkatGolongan::create($data);
  }

  /**
   * Ambil data pangkat golongan untuk edit
   * 
   * @param PangkatGolongan $pangkatGolongan
   * 
   * @return \App\Models\PangkatGolongan
   */
  public function getPangkatGolonganForEdit(PangkatGolongan $pangkatGolongan)
  {
    return $pangkatGolongan;
  }

  /**
   * Update data pangkat golongan
   * 
   * @param PangkatGolongan $pangkatGolongan
   * @param array $data
   * 
   * @return \App\Models\PangkatGolongan
   */
  public function updatePangkatGolongan(PangkatGolongan $pangkatGolongan, array $data)
  {
    $pangkatGolongan->update($data);
    return $pangkatGolongan;
  }

  /**
   * Hapus data pangkat golongan
   * 
   * @param \App\Models\PangkatGolongan $pangkatGolongan
   * 
   * @return void
   */
  public function destroyPangkatGolongan(PangkatGolongan $pangkatGolongan)
  {
    // cek apakah pangkat golongan masih dipakai oleh pegawai
    $pegawaiCount = $pangkatGolongan->users()->count();
    if ($pegawaiCount > 0) {
      throw new \Exception('Pangkat dan Golongan masih digunakan oleh ' . $pegawaiCount . ' pegawai.');
    }

    // hapus pangkat golongan
    $pangkatGolongan->delete();
  }
}
