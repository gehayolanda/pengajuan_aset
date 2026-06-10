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
        'deteled_at' => 'datetime'
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

}
