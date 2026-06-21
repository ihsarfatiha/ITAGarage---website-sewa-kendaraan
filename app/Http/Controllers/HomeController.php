<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Car;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $popularMotors = Motor::where('status', 'available')
                              ->orderBy('created_at', 'desc')
                              ->limit(6)
                              ->get();

        $popularCars = Car::where('status', 'available')
                          ->orderBy('created_at', 'desc')
                          ->limit(6)
                          ->get();
        
        return view('home', compact('popularMotors', 'popularCars'));
    }
}