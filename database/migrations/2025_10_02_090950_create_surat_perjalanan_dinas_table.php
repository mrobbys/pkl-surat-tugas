<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_perjalanan_dinas', function (Blueprint $table) {
            $table->id();
            $table->string('kepada_yth');
            $table->string('dari');
            $table->string('nomor_telaahan', 100);
            $table->date('tanggal_telaahan');
            $table->text('dasar_telaahan');
            $table->text('isi_telaahan');
            $table->string('nomor_surat_tugas', 100)->nullable();
            $table->string('nomor_nota_dinas', 100)->nullable();
            $table->text('perihal_kegiatan');
            $table->string('tempat_pelaksanaan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            // approval penyetuju satu
            $table->foreignId('penyetuju_satu_id')->nullable()->constrained('users');
            $table->enum('status_penyetuju_satu', ['pending', 'revisi', 'disetujui', 'ditolak'])->default('pending');
            $table->dateTime('tanggal_status_satu')->nullable();
            $table->text('catatan_satu')->nullable();
            // approveal penyetuju dua
            $table->foreignId('penyetuju_dua_id')->nullable()->constrained('users');
            $table->enum('status_penyetuju_dua', ['pending', 'revisi', 'disetujui', 'ditolak'])->default('pending');
            $table->dateTime('tanggal_status_dua')->nullable();
            $table->text('catatan_dua')->nullable();
            // status keseluruhan surat perjalanan dinas
            $table->enum('status', ['diajukan', 'disetujui_kabid', 'revisi_kabid', 'ditolak_kabid', 'disetujui_kadis', 'revisi_kadis', 'ditolak_kadis'])->default('diajukan');
            $table->foreignId('pembuat_id')->constrained('users');
            $table->index([
                'status',
                'pembuat_id',
                'nomor_telaahan',
                'nomor_surat_tugas',
                'nomor_nota_dinas',
                'tanggal_telaahan',
                'tanggal_mulai',
                'created_at',
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_perjalanan_dinas');
    }
};
