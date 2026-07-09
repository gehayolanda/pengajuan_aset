<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class PengajuanPemusnahan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengajuan_penghapusan_asset';

    protected $fillable = [
        'nomor_pengajuan',
        'aset_id',
        'sekolah_id',
        'diajukan_oleh',
        'alasan_penghapusan',
        'metode_penghapusan',
        'jumlah_diajukan',
        'keterangan',
        'dokumen_pendukung',
        'status',
        'divalidasi_oleh',
        'catatan_validasi',
        'tanggal_validasi',
    ];

    protected $casts = [
        'tanggal_validasi' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────
    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'divalidasi_oleh');
    }

    // ── Accessors / Labels ─────────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu'  => '<span class="badge bg-warning text-dark">Menunggu</span>',
            'diproses'  => '<span class="badge bg-info text-dark">Diproses</span>',
            'disetujui' => '<span class="badge bg-success">Disetujui</span>',
            'ditolak'   => '<span class="badge bg-danger">Ditolak</span>',
            default     => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getMetodeLabelAttribute(): string
    {
        return match ($this->metode_penghapusan) {
            'pemusnahan'  => 'Pemusnahan',
            default       => '-',
        };
    }

    // ── Auto-generate nomor pengajuan ──────────────────────────────────
    // PengajuanPemusnahan.php — generateNomor() tetap di model lama, tanpa tabel baru
public static function generateNomor(): string
{
    $year  = now()->format('Y');
    $month = now()->format('m');

    // lockForUpdate mengunci baris-baris bulan ini yang sudah ada,
    // sehingga request lain yang juga generateNomor() untuk bulan
    // yang sama akan menunggu transaction ini selesai (commit/rollback)
    // SELAMA sudah ada minimal satu baris untuk dikunci.
    $last = self::withTrashed() // ikutkan yang soft-deleted agar nomor tidak pernah dipakai ulang
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->lockForUpdate()
        ->count();

    return sprintf('PHA/%s/%s/%04d', $year, $month, $last + 1);
}
}
