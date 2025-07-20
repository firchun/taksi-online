<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\RuteTaksi;
use App\Models\Taksi;
use GuzzleHttp\RetryMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class TaksiController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Mobil',
        ];
        return view('admin.mobil.index', $data);
    }

    public function getMobilDataTable()
    {
        $mobil = Taksi::with('supir')->orderByDesc('id');

        return DataTables::of($mobil)
            ->addColumn('foto', function ($mobil) {
                return '<img src="' . Storage::url($mobil->foto_depan) . '" style="width:100px;">';
            })
            ->addColumn('supir', function ($mobil) {
                return '<strong>' . $mobil->supir->name . '</strong><br>' . $mobil->supir->email;
            })
            ->addColumn('mobil', function ($mobil) {
                return '<strong>' . $mobil->plat_nomor . '</strong><br>Merek Mobil : ' . $mobil->merek . '<br>Warna Mobil : ' . $mobil->warna;
            })
            ->addColumn('action', function ($mobil) {
                if ($mobil->verified == false) {

                    return '<div class="d-flex form-group"> <a href="' . route('mobil-verifikasi', $mobil->id) . '" class="btn btn-warning mx-2 btn-sm">Verifikasi</a><br><a href="' . route('mobil-tolak', $mobil->id) . '" class="btn btn-danger btn-sm">Tolak</a></div>';
                } else {
                    return '<span class="text-success">Terverifikasi</span>';
                }
            })
            ->addColumn('status_mobil', function ($mobil) {
                $status =  $mobil->aktif == 1 ? '<span class="text-success">Online</span>' : '<span class="text-muted">Offline</span>';
                return '<strong>' . $status . '</strong><br>' . $mobil->status;
            })
            ->rawColumns(['supir', 'foto', 'mobil', 'status_mobil', 'action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|string|max:255',
            'plat_nomor' => 'required|string',
            'merek' => 'required|string',
            'warna' => 'required|string',
            'jumlah_penumpang' => 'required|string',
            'foto_sim' => 'required|image',
            'foto_depan' => 'required|image',
            'foto_samping' => 'required|image',
        ]);

        $taksiData = [
            'id_user' => $request->input('id_user'),
            'plat_nomor' => $request->input('plat_nomor'),
            'merek' => $request->input('merek'),
            'warna' => $request->input('warna'),
            'jumlah_penumpang' => $request->input('jumlah_penumpang'),
            'status' => 'Tersedia',
            'aktif' => 0,
        ];
        if ($request->hasFile('foto_sim')) {
            $filename = Str::random(32) . '.' . $request->file('foto_sim')->getClientOriginalExtension();

            $image = $request->file('foto_sim');
            $image->storeAs('public/berkas', $filename);


            $file_path = 'public/berkas/' . $filename;
            $taksiData['foto_sim'] = isset($file_path) ? $file_path : '';
        }
        if ($request->hasFile('foto_depan')) {
            $filename = Str::random(32) . '.' . $request->file('foto_depan')->getClientOriginalExtension();

            $image = $request->file('foto_depan');
            $image->storeAs('public/berkas', $filename);


            $file_path = 'public/berkas/' . $filename;
            $taksiData['foto_depan'] = isset($file_path) ? $file_path : '';
        }
        if ($request->hasFile('foto_samping')) {
            $filename = Str::random(32) . '.' . $request->file('foto_samping')->getClientOriginalExtension();

            $image = $request->file('foto_samping');
            $image->storeAs('public/berkas', $filename);


            $file_path = 'public/berkas/' . $filename;
            $taksiData['foto_samping'] = isset($file_path) ? $file_path : '';
        }
        if ($request->hasFile('foto_dalam')) {
            $filename = Str::random(32) . '.' . $request->file('foto_dalam')->getClientOriginalExtension();

            $image = $request->file('foto_dalam');
            $image->storeAs('public/berkas', $filename);


            $file_path = 'public/berkas/' . $filename;
            $taksiData['foto_dalam'] = isset($file_path) ? $file_path : '';
        }
        $type = 'danger';
        $message = 'Gagal menyimpan data';
        if ($request->filled('id')) {
            $Taksi = Taksi::find($request->input('id'));
            if (!$Taksi) {
                return response()->json(['message' => 'Taksi not found'], 404);
            }

            $Taksi->update($taksiData);
            $type = 'success';
            $message = 'Taksi updated successfully';
        } else {
            $taksi = Taksi::create($taksiData);
            if ($request->input('id_rute')) {
                foreach ($request->input('id_rute') as $id_rute) {
                    RuteTaksi::create([
                        'id_taksi' => $taksi->id,
                        'id_rute' => $id_rute
                    ]);
                }
            }
            $type = 'success';
            $message = 'Taksi created successfully';
        }
        session()->flash($type, $message);
        return back()->withInput();
    }
    public function addRute(Request $request)
    {
        $cek_rute = RuteTaksi::where('id_taksi', $request->input('id_taksi'))->where('id_rute', $request->input('id_rute'))->first();
        if (!$cek_rute) {

            RuteTaksi::create([
                'id_taksi' => $request->input('id_taksi'),
                'id_rute' => $request->input('id_rute'),
            ]);
            session()->flash('success', 'Berhasil Menambah Rute Baru');
            return back();
        } else {
            session()->flash('danger', 'Gagal Rute sudah ada');
            return back();
        }
    }
    public function destroyRute($id)
    {
        $rute = RuteTaksi::find($id);
        $rute->delete();
        session()->flash('success', 'Berhasil Menghapus rute');
        return back();
    }
    public function updateStatus($id)
    {
        $taksi = Taksi::find($id);
        if ($taksi->aktif == 0) {
            $taksi->aktif = 1;
        } else {
            $taksi->aktif = 0;
        }
        $taksi->save();
        session()->flash('success', 'Berhasil Update status');
        return back();
    }
    public function verifikasi($id)
    {
        $taksi = Taksi::find($id);
        $taksi->verified = true;
        $taksi->save();
        session()->flash('success', 'Berhasil verifikasi');
        return back();
    }
    public function tolak($id)
    {
        $taksi = Taksi::find($id);
        $taksi->delete();
        session()->flash('success', 'Berhasil Menolak');
        return back();
    }
}