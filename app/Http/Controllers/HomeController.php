<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resort;
use App\Models\Package;

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
        $sum_villas = Resort::whereHas('category', function($query) {
            $query->where('name', 'villa');
        })->count();

        $sum_activities = Resort::whereHas('category', function($query){
            $query->where('name','activity');
        })->count();

        $sum_packages = Package::count();

        // return view('home');
        return view('admin.index', ['sum_villas' => $sum_villas, 'sum_activities' => $sum_activities, 'sum_packages' => $sum_packages]);
    }
}
