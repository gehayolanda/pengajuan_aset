<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Sekolah;
use App\Models\PengajuanPemusnahan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSekolah  = Sekolah::count();
        $totalAset     = Aset::count();
        $totalAsetHapus = Aset::onlyTrashed()->count();

        $totalPengajuan   = PengajuanPemusnahan::count();
        $pengajuanMenunggu = PengajuanPemusnahan::where('status', 'menunggu')->count();
        $pengajuanDisetujui = PengajuanPemusnahan::where('status', 'disetujui')->count();
        $pengajuanDitolak  = PengajuanPemusnahan::where('status', 'ditolak')->count();

        // Data chart: pengajuan per bulan (12 bulan terakhir)
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartData[] = [
                'bulan'     => $date->translatedFormat('M Y'),
                'menunggu'  => PengajuanPemusnahan::whereYear('created_at', $date->year)
                                  ->whereMonth('created_at', $date->month)
                                  ->where('status', 'menunggu')->count(),
                'disetujui' => PengajuanPemusnahan::whereYear('created_at', $date->year)
                                  ->whereMonth('created_at', $date->month)
                                  ->where('status', 'disetujui')->count(),
                'ditolak'   => PengajuanPemusnahan::whereYear('created_at', $date->year)
                                  ->whereMonth('created_at', $date->month)
                                  ->where('status', 'ditolak')->count(),
            ];
        }

        $pengajuanTerbaru = PengajuanPemusnahan::with(['aset', 'sekolah', 'pengaju'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalSekolah',
            'totalAset',
            'totalAsetHapus',
            'totalPengajuan',
            'pengajuanMenunggu',
            'pengajuanDisetujui',
            'pengajuanDitolak',
            'chartData',
            'pengajuanTerbaru'
        ));
    }
}
