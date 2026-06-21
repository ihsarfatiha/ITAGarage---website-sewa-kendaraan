<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function catalog(Request $request)
    {
        $query = Car::query();

        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }

        // Car specific filters
        if ($request->has('transmission') && $request->transmission != '') {
            $query->where('transmission', $request->transmission);
        }
        
        if ($request->has('seats') && $request->seats != '') {
            $query->where('seats', '>=', $request->seats);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $cars = $query->paginate(12);

        // We can reuse the catalog view but pass specific data
        return view('catalog_car', compact('cars'));
    }

    public function detail($id)
    {
        $car = Car::with(['reviews.user'])->findOrFail($id);
        return view('detail_car', compact('car'));
    }
}
