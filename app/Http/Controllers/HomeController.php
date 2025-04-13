<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        $totalProducts = Product::count();
        $totalUsers = User::count();

        $monthlyRevenue = Sale::selectRaw("TO_CHAR(created_at, 'Month') AS month, SUM(total_amount) AS total")
            ->groupByRaw("TO_CHAR(created_at, 'Month'), EXTRACT(MONTH FROM created_at)")
            ->orderByRaw("EXTRACT(MONTH FROM created_at)")
            ->pluck('total', 'month');

        return view('home', compact(
            'totalProducts',
            'totalUsers',
            'monthlyRevenue'
        ));
    }

    public function blank()
    {
        return view('layouts.blank-page');
    }
}
