<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'reviewable_id' => 'required|integer',
            'reviewable_type' => 'required|string|in:App\Models\Car,App\Models\Motor',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'reviewable_id' => $request->reviewable_id,
            'reviewable_type' => $request->reviewable_type,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Ulasan berhasil ditambahkan!');
    }
}
