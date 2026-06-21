<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use Illuminate\Http\Request;

class MotorController extends Controller
{
    public function catalog(Request $request)
    {
        $query = Motor::query();

        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $motors = $query->paginate(12);

        return view('catalog', compact('motors'));
    }

    public function detail($id)
    {
        $motor = Motor::with(['reviews.user'])->findOrFail($id);
        return view('detail', compact('motor'));
    }
}