<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Penumpang;
use App\Models\Taksi;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_user' => 'required|max:255',
            'id_taksi' => 'required|max:255',
            'id_rute_asal' => 'required|max:255',
            'id_rute_tujuan' => 'required|max:255',
            'jumlah_penumpang' => 'required|integer|min:1',
            'nama' => 'nullable|array|min:1', // Nama boleh kosong
            'nama.*' => 'nullable|string|max:255',
        ]);

        $pemesananData = [
            'id_user' => $request->input('id_user'),
            'id_taksi' => $request->input('id_taksi'),
            'id_rute_asal' => $request->input('id_rute_asal'),
            'id_rute_tujuan' => $request->input('id_rute_tujuan'),
            'jumlah_penumpang' => $request->input('jumlah_penumpang'),
        ];

        $type = 'danger';
        $message = 'Gagal menyimpan data';

        // Ambil data taksi hanya sekali
        $taksi = Taksi::find($request->input('id_taksi'));
        if (!$taksi) {
            session()->flash('danger', 'Taksi tidak ditemukan');
            return back()->withInput();
        }

        $kapasitasTaksi = $taksi->jumlah_penumpang;
        $totalPesanan = Pemesanan::where('id_taksi', $taksi->id)->where('pesanan_selesai', 0)->sum('jumlah_penumpang');
        $sisaKapasitas = $kapasitasTaksi - $totalPesanan;

        // Periksa kapasitas
        if ($totalPesanan >= $kapasitasTaksi) {
            $message = 'Taksi telah penuh';
        } elseif ($request->input('jumlah_penumpang') > $sisaKapasitas) {
            $message = 'Jumlah penumpang melebihi kapasitas';
        } else {
            // Jika ID pemesanan sudah ada, lakukan update
            if ($request->filled('id')) {
                $pemesanan = Pemesanan::find($request->input('id'));
                if (!$pemesanan) {
                    return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
                }
                $pemesanan->update($pemesananData);
                $type = 'success';
                $message = 'Pemesanan berhasil diperbarui';
            } else {
                // Buat pesanan baru
                $pemesanan = Pemesanan::create($pemesananData);

                // Update status taksi jika kapasitas penuh
                if ($totalPesanan + $request->input('jumlah_penumpang') >= $kapasitasTaksi) {
                    $taksi->update(['status' => 'Full']);
                }

                // Simpan nama penumpang jika ada
                if (!empty($request->nama)) {
                    $penumpangData = array_map(function ($nama) use ($pemesanan) {
                        return [
                            'id_pemesanan' => $pemesanan->id,
                            'nama' => $nama,
                        ];
                    }, $request->nama);

                    Penumpang::insert($penumpangData); // Batch insert untuk menghemat query
                }

                $type = 'success';
                $message = 'Pemesanan berhasil dibuat';
            }
        }

        // Simpan pesan ke session
        session()->flash($type, $message);

        // Redirect kembali
        return back()->withInput();
    }
    public function pesananSelesai($id)
    {
        $pemesanan = Pemesanan::find($id);
        $pemesanan->pesanan_selesai = 1;
        $pemesanan->save();
        //pengecekan
        $cek_taksi = Taksi::find($pemesanan->id_taksi)->first()->jumlah_penumpang;
        $cek_pemesanan = Pemesanan::where('id_taksi', $pemesanan->id_taksi)->where('pesanan_selesai', 0)->sum('jumlah_penumpang');
        $cek_penumpang = $cek_taksi - $cek_pemesanan;
        if ($cek_penumpang != 0) {
            $taksi = Taksi::find($pemesanan->id_taksi);
            $taksi->status = 'Tersedia';
            $taksi->save();
        }

        session()->flash('berhasil menyelesaikan pesanana');
        return back();
    }
}