<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Rute;
use App\Models\RuteTaksi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RuteController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Rute Mobil',
        ];
        return view('admin.rute.index', $data);
    }
    public function list()
    {
        $rute = Rute::all();
        return response()->json($rute);
    }
    public function rute_penjemputan($id_taksi)
    {
        $pesanan = Pemesanan::with('asal')
            ->select(['id_rute_asal'])
            ->where('id_taksi', $id_taksi)
            ->where('pesanan_selesai', 0)
            ->groupBy('id_rute_asal')
            ->get();

        return response()->json($pesanan);
    }
    public function rute_taksi($id_taksi)
    {
        $pesanan = Pemesanan::with(['asal', 'tujuan'])
            ->select(['id_rute_asal', 'id_rute_tujuan'])
            ->where('id_taksi', $id_taksi)
            ->where('pesanan_selesai', 0)
            ->groupBy('id_rute_asal', 'id_rute_tujuan')
            ->get();



        return response()->json($pesanan);
    }
    public function getRuteDataTable()
    {
        $rute = Rute::orderByDesc('id');

        return DataTables::of($rute)
            ->addColumn('action', function ($rute) {
                return view('admin.rute.components.actions', compact('rute'));
            })
            ->addColumn('mobil', function ($rute) {
                $mobil = RuteTaksi::where('id_rute', $rute->id)->count();
                return $mobil . ' Mobil';
            })
            ->rawColumns(['action', 'mobil'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
        ]);

        $ruteData = [
            'nama_lokasi' => $request->input('nama_lokasi'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
        ];

        if ($request->filled('id')) {
            $Rute = Rute::find($request->input('id'));
            if (!$Rute) {
                return response()->json(['message' => 'Rute not found'], 404);
            }

            $Rute->update($ruteData);
            $message = 'Rute updated successfully';
        } else {
            Rute::create($ruteData);
            $message = 'Rute created successfully';
        }

        return response()->json(['message' => $message]);
    }
    public function destroy($id)
    {
        $rute = Rute::find($id);

        if (!$rute) {
            return response()->json(['message' => 'Rute not found'], 404);
        }

        $rute->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
    public function edit($id)
    {
        $Rute = Rute::find($id);

        if (!$Rute) {
            return response()->json(['message' => 'Rute not found'], 404);
        }

        return response()->json($Rute);
    }
}
