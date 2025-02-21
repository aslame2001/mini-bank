<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $customerCount = Customer::count();
        $transactionCount = \App\Models\Transaction::count();
        return view('index', compact('customerCount', 'transactionCount'));
    }

    public function index()
    {
        $customers = Customer::withCount('transactions')->get();
        //return $customers;
        return view('customers', compact('customers'));
    }

    public function create()
    {
        return view('add-customer');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:6',
        ]);

        try {
            Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('customers.index');
        } catch (\Exception $e) {
            return $e->getMessage();
            return back()->withErrors(['error' => 'An error occurred while creating the customer.']);
        }
    }
}
