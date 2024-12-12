<?php

namespace App\Http\Controllers;

use App\Models\transaction_statis;
use App\Models\transaction_status;
use Illuminate\Http\Request;

class TransactionStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    
    
    public function show(transaction_status $transaction_statis)
    {
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, transaction_status $transaction_statis)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(transaction_status $transaction_statis)
    {
        //
    }
}
