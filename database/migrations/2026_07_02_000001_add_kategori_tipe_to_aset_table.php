<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aset', function (Blueprint $table) {
            $table->enum('kategori', ['mebel', 'elektronik', 'sarana_pembelajaran'])
                  ->nullable()
                  ->after('nama_aset')
                  ->comment('Kategori barang: mebel, elektronik, sarana_pembelajaran');

            $table->string('tipe_barang', 100)
                  ->nullable()
                  ->after('kategori')
                  ->comment('Tipe/jenis spesifik barang');
        });
    }

    public function down(): void
    {
        Schema::table('aset', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'tipe_barang']);
        });
    }
};
