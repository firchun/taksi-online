<?php

namespace App\Http\Controllers;

use App\Models\Taksi;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UlasanController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Ulasan Pelanggan'
        ];
        return view('admin.ulasan.index', $data);
    }
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'id_pemesanan' => 'required|exists:pemesanan,id', // Misal tabel pemesanan ada
            'id_user' => 'required|exists:users,id',
            'id_taksi' => 'required|exists:taksi,id',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string',
        ]);

        // Simpan data ulasan ke database
        $ulasan = new Ulasan();
        $ulasan->id_pemesanan = $request->input('id_pemesanan');
        $ulasan->id_user = $request->input('id_user');
        $ulasan->id_taksi = $request->input('id_taksi');
        $ulasan->rating = $request->input('rating');
        $ulasan->ulasan = $request->input('ulasan');
        $ulasan->save();

        // Redirect atau return response sesuai kebutuhan
        return redirect()->back()->with('success', 'Ulasan berhasil dikirim.');
    }
    public function getUlasanDataTable()
    {

        $ulasan = Ulasan::with(['user'])->orderByDesc('id');
        if (Auth::user()->role == 'Supir') {
            $id_user = Auth::id();
            $taksi = Taksi::where('id_user', $id_user)->first();

            $ulasan->where('id_taksi', $taksi->id);
        }

        return DataTables::of($ulasan)
            ->addColumn('tanggal', function ($ulasan) {
                return $ulasan->created_at->format('d-m-Y');
            })
            ->addColumn('bintang', function ($ulasan) {
                $stars = '';
                for ($i = 1; $i <= 5; $i++) {
                    $stars .= '<span class="bx bx-star ' . ($i <= $ulasan->rating ? 'text-warning' : '') . '"></span>';
                }
                return $stars;
            })
            ->rawColumns(['bintang', 'tanggal'])
            ->make(true);
    }
}
