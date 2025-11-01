<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SuratPerjalananDinas extends Model
{
    protected $guarded = [];

    public function penyetujuSatu()
    {
        return $this->belongsTo(User::class, 'penyetuju_satu_id');
    }

    public function penyetujuDua()
    {
        return $this->belongsTo(User::class, 'penyetuju_dua_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'pembuat_id');
    }

    public function pegawaiDitugaskan()
    {
        return $this->belongsToMany(User::class, 'penugasan_pegawai', 'surat_id', 'user_id');
    }
}
