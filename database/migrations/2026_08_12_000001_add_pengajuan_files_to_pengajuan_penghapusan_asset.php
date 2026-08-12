<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_penghapusan_asset', function (Blueprint $table) {
            $table->string('surat_pengajuan')->nullable()->after('jumlah_diajukan');
            $table->string('berita_acara')->nullable()->after('surat_pengajuan');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_penghapusan_asset', function (Blueprint $table) {
            $table->dropColumn(['surat_pengajuan', 'berita_acara']);
        });
    }
};
