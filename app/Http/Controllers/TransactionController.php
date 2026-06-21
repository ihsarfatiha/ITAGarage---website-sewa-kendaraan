<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Motor;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function history()
    {
        $transactions = Transaction::where('user_id', Auth::id())
                                   ->with(['motor', 'car', 'payment'])
                                   ->orderBy('created_at', 'desc')
                                   ->get(); // Get all to filter in memory/views easily or separate queries

        $rentTransactions = $transactions->where('transaction_type', 'rent');
        $purchaseTransactions = $transactions->where('transaction_type', 'purchase');

        return view('history', compact('transactions', 'rentTransactions', 'purchaseTransactions'));
    }

    public function pay(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        if ($transaction->transaction_type == 'rent') {
            $transaction->update(['status' => 'ongoing']);
        } else {
            $transaction->update(['status' => 'completed']);
        }

        if ($transaction->payment) {
            $transaction->payment->update(['payment_status' => 'paid']);
        }

        return redirect()->route('history')->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    public function cancel(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->update(['status' => 'cancelled']);
        
        if ($transaction->payment) {
            $transaction->payment->update(['payment_status' => 'failed']);
        }

        return redirect()->route('history')->with('success', 'Transaksi berhasil dibatalkan.');
    }

    public function complete(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->update(['status' => 'completed']);

        // Redirect to appropriate tab?? For now just history
        return redirect()->route('history')->with('success', 'Peminjaman selesai. Terima kasih!');
    }

    public function rent(Request $request)
    {
        // Polymorphic-like validation or check type
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string',
        ]);

        $motor = null;
        $car = null;
        $vehicleType = '';
        $totalPrice = 0;

        // Check if it's a car (from detail_car) or motor (from detail)
        if ($request->has('rentable_type') && $request->rentable_type == 'App\Models\Car') {
            $car = \App\Models\Car::findOrFail($request->rentable_id);
            $vehicleType = 'car';
            $price = $car->daily_rent_price;
        } elseif ($request->has('motor_id')) {
            $motor = Motor::findOrFail($request->motor_id);
            $vehicleType = 'motor';
            $price = $motor->daily_rent_price;
        } else {
            return back()->withErrors(['message' => 'Kendaraan tidak valid']);
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $duration = $startDate->diffInDays($endDate) + 1;
        $totalPrice = $duration * $price;

        Transaction::create([
            'user_id' => Auth::id(),
            'vehicle_type' => $vehicleType,
            'motor_id' => $motor ? $motor->id : null,
            'car_id' => $car ? $car->id : null,
            'transaction_type' => 'rent',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration_days' => $duration,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        return redirect()->route('history')->with('success', 'Peminjaman berhasil diajukan!');
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,transfer,credit_card',
            'notes' => 'nullable|string'
        ]);

        $motor = null;
        $car = null;
        $vehicleType = '';
        $totalPrice = 0;

        if ($request->has('car_id')) {
            $car = \App\Models\Car::findOrFail($request->car_id);
            $vehicleType = 'car';
            $totalPrice = $car->purchase_price;
        } elseif ($request->has('motor_id')) {
            $motor = Motor::findOrFail($request->motor_id);
            $vehicleType = 'motor';
            $totalPrice = $motor->purchase_price;
        } else {
            return back()->withErrors(['message' => 'Kendaraan tidak valid']);
        }

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'vehicle_type' => $vehicleType,
            'motor_id' => $motor ? $motor->id : null,
            'car_id' => $car ? $car->id : null,
            'transaction_type' => 'purchase',
            'start_date' => now(),
            'total_price' => $totalPrice,
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        Payment::create([
            'transaction_id' => $transaction->id,
            'amount' => $totalPrice,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'payment_date' => now()
        ]);

        return redirect()->route('history')->with('success', 'Pembelian berhasil diajukan!');
    }
}