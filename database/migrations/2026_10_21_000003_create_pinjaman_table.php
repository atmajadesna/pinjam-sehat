<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->unsignedBigInteger('pokok');
            $table->unsignedSmallInteger('tenor');
            $table->decimal('bunga_persen', 5, 2)->default(1.50);
            $table->string('keperluan')->default('Kebutuhan konsumtif');
            $table->string('status')->default('Pengajuan');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
