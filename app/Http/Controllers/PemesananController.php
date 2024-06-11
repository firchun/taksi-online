<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Taksi;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'id_user' => 'required|max:255',
            'id_taksi' => 'required|max:255',
            'id_rute_asal' => 'required|max:255',
            'id_rute_tujuan' => 'required|max:255',
            'jumlah_penumpang' => 'required|max:255',
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
        //pengecekan
        $cek_taksi = Taksi::find($request->input('id_taksi'))->first()->jumlah_penumpang;
        $cek_pemesanan = Pemesanan::where('id_taksi', $request->input('id_taksi'))->where('pesanan_selesai', 0)->sum('jumlah_penumpang');
        $cek_penumpang = $cek_taksi - $cek_pemesanan;
        if ($cek_pemesanan < $cek_taksi) {
            if ($request->input('jumlah_penumpang') > $cek_penumpang) {
                $type = 'danger';
                $message = 'Jumlah penumpang melebihi kapasitas';
            } else {
                if ($request->filled('id')) {
                    $Pemesanan = Pemesanan::find($request->input('id'));
                    if (!$Pemesanan) {
                        return response()->json(['message' => 'Pemesanan not found'], 404);
                    }

                    $Pemesanan->update($pemesananData);
                    $type = 'success';
                    $message = 'Pemesanan updated successfully';
                } else {
                    $Pemesanan = Pemesanan::create($pemesananData);
                    if ($cek_pemesanan + $request->input('jumlah_penumpang') == $cek_taksi) {
                        $taksi = Taksi::find($request->input('id_taksi'));
                        $taksi->status = 'Full';
                        $taksi->save();
                    }
                    $type = 'success';
                    $message = 'Taksi created successfully';
                }
            }
        } else {
            $type = 'danger';
            $message = 'Taksi telah penuh';
        }
        session()->flash($type, $message);
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
