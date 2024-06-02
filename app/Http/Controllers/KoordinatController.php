<?php

namespace App\Http\Controllers;

use App\Models\RiwayatKoordinat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KoordinatController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $data['created_at'] = now();
        $data['updated_at'] = now();
        $koordinat = DB::table('riwayat_koordinat')->insert($data);
        if ($koordinat) {
            return response()->json(['message' => 'Berhasil mengirim koordinat']);
        } else {
            return response()->json(['message' => 'gagal mengirim koordinat']);
        }
    }
    public function getKoordinat($id_taksi)
    {
        $koordinat = RiwayatKoordinat::where('id_taksi', $id_taksi)->latest()->first();
        return response()->json($koordinat);
    }
}
