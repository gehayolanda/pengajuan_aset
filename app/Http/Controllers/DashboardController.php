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
        $user = Auth::user();

        if ($user->hasRole('operator_sekolah')) {
            $sekolah   = Sekolah::where('operator_id', $user->id)->first();
            $sekolahId = $sekolah?->id;

            $totalSekolah      = null;
            $totalAset         = Aset::where('sekolah_id', $sekolahId)->count();
            $totalAsetHapus    = Aset::onlyTrashed()->where('sekolah_id', $sekolahId)->count();
            $totalPengajuan    = PengajuanPemusnahan::where('diajukan_oleh', $user->id)->count();
            $pengajuanMenunggu  = PengajuanPemusnahan::where('diajukan_oleh', $user->id)->where('status', 'diajukan')->count();
            $pengajuanDisetujui = PengajuanPemusnahan::where('diajukan_oleh', $user->id)->where('status', 'disetujui')->count();
            $pengajuanDitolak   = PengajuanPemusnahan::where('diajukan_oleh', $user->id)->where('status', 'ditolak')->count();

            $pengajuanTerbaru      = PengajuanPemusnahan::with(['aset', 'sekolah', 'pengaju'])
                ->where('diajukan_oleh', $user->id)->latest()->take(5)->get();
            $pengajuanMenungguList = PengajuanPemusnahan::with(['aset', 'sekolah'])
                ->where('diajukan_oleh', $user->id)->where('status', 'diajukan')->latest()->take(5)->get();

        } elseif ($user->hasRole('kepala_dinas')) {
            $totalSekolah      = null;
            $totalAset         = null;
            $totalAsetHapus    = null;
            $totalPengajuan    = PengajuanPemusnahan::count();
            $pengajuanMenunggu  = PengajuanPemusnahan::where('status', 'diajukan')->count();
            $pengajuanDisetujui = PengajuanPemusnahan::where('status', 'disetujui')->count();
            $pengajuanDitolak   = PengajuanPemusnahan::where('status', 'ditolak')->count();

            $pengajuanTerbaru      = PengajuanPemusnahan::with(['aset', 'sekolah', 'pengaju'])->latest()->take(5)->get();
            $pengajuanMenungguList = PengajuanPemusnahan::with(['aset', 'sekolah'])
                ->where('status', 'diajukan')->latest()->take(5)->get();

        } else {
            // admin
            $totalSekolah      = Sekolah::count();
            $totalAset         = Aset::count();
            $totalAsetHapus    = Aset::onlyTrashed()->count();
            $totalPengajuan    = PengajuanPemusnahan::count();
            $pengajuanMenunggu  = PengajuanPemusnahan::where('status', 'diajukan')->count();
            $pengajuanDisetujui = PengajuanPemusnahan::where('status', 'disetujui')->count();
            $pengajuanDitolak   = PengajuanPemusnahan::where('status', 'ditolak')->count();

            $pengajuanTerbaru      = PengajuanPemusnahan::with(['aset', 'sekolah', 'pengaju'])->latest()->take(5)->get();
            $pengajuanMenungguList = PengajuanPemusnahan::with(['aset', 'sekolah'])
                ->where('status', 'diajukan')->latest()->take(5)->get();
        }

        return view('dashboard.index', compact(
            'totalSekolah',
            'totalAset',
            'totalAsetHapus',
            'totalPengajuan',
            'pengajuanMenunggu',
            'pengajuanDisetujui',
            'pengajuanDitolak',
            'pengajuanTerbaru',
            'pengajuanMenungguList'
        ));
    }
}
