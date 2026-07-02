<?php

use App\Models\Aset;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Konversi semua kode lama berformat AST-xxx menjadi format kode barang 2.07...
        $asets = Aset::withTrashed()
            ->where('kode_aset', 'like', 'AST-%')
            ->orderBy('id')
            ->get();

        foreach ($asets as $aset) {
            // Pakai prefix sesuai kategori bila ada; kalau kosong pakai prefix default (2.07.99.01)
            $aset->kode_aset = Aset::generateKode($aset->kategori ?? '');
            $aset->save();
        }
    }

    public function down(): void
    {
        // Konversi tidak dapat dikembalikan otomatis (kode lama tidak disimpan).
    }
};
