<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pengajuan_penghapusan_asset MODIFY COLUMN status ENUM('menunggu','diajukan','diproses','disetujui','ditolak') NOT NULL DEFAULT 'diajukan'");
        DB::statement("UPDATE pengajuan_penghapusan_asset SET status = 'diajukan' WHERE status = 'menunggu'");
        DB::statement("ALTER TABLE pengajuan_penghapusan_asset MODIFY COLUMN status ENUM('diajukan','diproses','disetujui','ditolak') NOT NULL DEFAULT 'diajukan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pengajuan_penghapusan_asset MODIFY COLUMN status ENUM('menunggu','diajukan','diproses','disetujui','ditolak') NOT NULL DEFAULT 'menunggu'");
        DB::statement("UPDATE pengajuan_penghapusan_asset SET status = 'menunggu' WHERE status = 'diajukan'");
        DB::statement("ALTER TABLE pengajuan_penghapusan_asset MODIFY COLUMN status ENUM('menunggu','diproses','disetujui','ditolak') NOT NULL DEFAULT 'menunggu'");
    }
};
