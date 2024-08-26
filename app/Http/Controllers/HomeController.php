<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Pemesanan;
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
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'users' => User::count(),
            'customers' => Customer::count()
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
