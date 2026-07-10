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
            'diajukan'  => '<span class="badge bg-warning text-dark">Diajukan</span>',
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
    // Format: PHA/{tahun}/{bulan}/{urutan}, urutan reset tiap bulan.
    public static function generateNomor(): string
    {
        $year  = now()->format('Y');
        $month = now()->format('m');

        // withTrashed() supaya nomor pengajuan yang sudah soft-deleted tetap
        // dihitung (masih terikat unique constraint di DB, jadi urutannya
        // tidak boleh dipakai ulang). lockForUpdate() mengunci baris-baris
        // bulan ini yang sudah ada, sehingga request lain yang bersamaan
        // menunggu transaction ini selesai sebelum menghitung urutan berikutnya.
        $last = self::withTrashed()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->lockForUpdate()
            ->count();

        return sprintf('PHA/%s/%s/%04d', $year, $month, $last + 1);
    }
}
