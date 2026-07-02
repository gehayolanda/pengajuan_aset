<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aset extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "aset";

    protected $fillable = [
        'sekolah_id', 'nama_aset', 'kategori', 'tipe_barang', 'kode_aset', 'kondisi', 'jumlah', 'satuan', 'tahun_pengadaan', 'harga_perolehan', 'lokasi', 'keterangan', 'foto_bukti'
    ];

    // Daftar kategori barang beserta label tampilannya
    public const KATEGORI = [
        'mebel'               => 'Mebel',
        'elektronik'          => 'Elektronik',
        'sarana_pembelajaran' => 'Sarana Pembelajaran',
    ];

    protected $casts = [
        'harga_perolehan' => 'decimal:2',
        'deleted_at' => 'datetime'
    ];

    // relasi
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function scopeTerhapus($query)
    {
        return $query->onlyTrashed();
    }

    public function scopeTermasukTerhapus($query)
    {
        return $query->withTrashed();
    }

    public function sudahDihapus(): bool
    {
        return $this->trashed();
    }

    public function pulihkan(): bool
    {
        return $this->restore();
    }

    public function hapusPermanen(): bool
    {
        return $this->forceDelete();
    }

    // Label kategori untuk tampilan
    public function kategoriLabel(): string
    {
        return self::KATEGORI[$this->kategori] ?? '-';
    }

    // Harga dalam format kurs Indonesia, contoh: Rp 12.000.000,00
    public function hargaFormat(): string
    {
        return $this->harga_perolehan !== null
            ? 'Rp ' . number_format((float) $this->harga_perolehan, 2, ',', '.')
            : '-';
    }

    // Prefix kode barang per kategori (format: golongan.bidang.kelompok.sub)
    public static function kodePrefix(string $kategori): string
    {
        return match ($kategori) {
            'mebel'               => '2.07.01.01',
            'elektronik'          => '2.07.02.01',
            'sarana_pembelajaran' => '2.07.03.01',
            default               => '2.07.99.01',
        };
    }

    // Membuat kode barang otomatis, contoh: 2.07.02.01.020
    public static function generateKode(string $kategori): string
    {
        $prefix = self::kodePrefix($kategori);

        $last = self::withTrashed()
            ->where('kode_aset', 'like', $prefix . '.%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(kode_aset, ".", -1) AS UNSIGNED) DESC')
            ->value('kode_aset');

        $next = $last ? ((int) substr($last, strrpos($last, '.') + 1)) + 1 : 1;

        return $prefix . '.' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

}
