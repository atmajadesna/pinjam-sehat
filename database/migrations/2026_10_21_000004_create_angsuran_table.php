<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('angsuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_id')->constrained('pinjaman')->cascadeOnDelete();
            $table->unsignedSmallInteger('angsuran_ke');
            $table->date('jatuh_tempo');
            $table->date('tanggal_bayar')->nullable();
            $table->unsignedBigInteger('jumlah');
            $table->unsignedBigInteger('denda')->default(0);
            $table->string('status')->default('Belum bayar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};
