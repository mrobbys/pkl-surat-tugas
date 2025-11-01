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
        Schema::create('pangkat_golongans', function (Blueprint $table) {
            $table->id();
            $table->string('pangkat', 100)->nullable(false);
            $table->string('golongan', 10)->nullable(false);
            $table->char('ruang', 1)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pangkat_golongans');
    }
};
