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
        'sekolah_id', 'nama_aset', 'kode_aset', 'kondisi', 'jumlah', 'satuan', 'tahun_pengadaan', 'harga_perolehan', 'lokasi', 'keterangan', 'foto_bukti'
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

    public static function generateKode(): string
    {
        $last = self::withTrashed()
            ->where('kode_aset', 'like', 'AST-%')
            ->orderByRaw('CAST(SUBSTRING(kode_aset, 5) AS UNSIGNED) DESC')
            ->value('kode_aset');

        $next = $last ? ((int) substr($last, 4)) + 1 : 1;

        return 'AST-' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

}
