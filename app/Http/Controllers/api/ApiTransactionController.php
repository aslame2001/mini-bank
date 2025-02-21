<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ApiTransactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $customer = $request->user();

        $today = now()->startOfDay();
        $transactionCount = Transaction::where('customer_id', $customer->id)
            ->where('created_at', '>=', $today)
            ->count();

        if ($transactionCount >= 5) {
            return response()->json(['message' => 'Daily transaction limit exceeded'], 403);
        }

        if ($request->type === 'debit' && $customer->balance < $request->amount) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }

        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'type' => $request->type,
            'amount' => $request->amount,
        ]);

        if ($request->type === 'credit') {
            $customer->balance += $request->amount;
        } else {
            $customer->balance -= $request->amount;
        }
        $customer->save();

        return response()->json(['message' => 'Transaction successful', 'transaction' => $transaction]);
    }
}

