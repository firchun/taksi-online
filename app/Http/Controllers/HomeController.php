<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Pemesanan;
use App\Models\Taksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $query = Taksi::query();
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('merek', 'like', "%$search%")
                    ->orWhere('plat_nomor', 'like', "%$search%")
                    ->orWhereHas('supir', function ($supirQuery) use ($search) {
                        $supirQuery->where('name', 'like', "%$search%");
                    });
            });
        }
        if (Auth::user()->role == 'Supir') {
            $query->where('id_user', Auth::id());
        }
        if (Auth::user()->role == 'User') {
            $query->where('verified', true);
        }
        $data = [
            'title' => 'Dashboard',
            'users' => User::count(),
            'customers' => Customer::count(),
            'taksi' => $query->get(),
        ];
        return view('admin.dashboard', $data);
    }
    public function riwayatUser()
    {
        $data = [
            'title' => 'Data Booking',
            'pemesanan' => Pemesanan::with(['mobil'])->where('id_user', Auth::id())->get(),
        ];
        return view('admin.riwayat_user', $data);
    }
}
