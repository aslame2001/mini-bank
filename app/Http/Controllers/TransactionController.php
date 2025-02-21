<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index($id)
    {
        $customer = Customer::find($id);
        $transactions = Transaction::where('customer_id', $id)->get();

        //return $transactions;
        return view('transactions', compact('transactions'));
    }
}
