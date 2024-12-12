<?php

namespace App\Http\Controllers;

use App\Models\transactions;
use App\Models\loans;
use App\Models\accounts;

use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if(!$user->hasRole('admin')){
            return response(['message' => 'Unauthorized'], 403);
        }

        $transactions = transactions::with('status')->get();

        return response(['message' => 'Transactions retrieved successfully', 'transactions' => $transactions], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'transaction_type' => 'required|string',
            'account_id' => 'required|integer',
            'amount' => 'required|numeric',
        ]);

        $status = 1;

        

        $user = auth()->user();

        if(!$user->hasRole('admin')){
            return response(['message' => 'Unauthorized'], 403);
        }

        $transaction = transactions::create([
            'account_id' => $request->account_id,
            'transaction_type' => $request->transaction_type,
            'amount' => $request->amount,
            'status' => $status
        ]);

        if($transaction->transaction_type == 'loan'){
            $loan = loans::create([
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'status' => $status
            ]);

            return response(['message' => 'Transaction created successfully', 'transaction' => $transaction, 'loan' => $loan], 200);
        }

        return response(['message' => 'Transaction created successfully', 'transaction' => $transaction], 200);


    }

    public function showAllTransactionWithLoan(Request $request)
{
    // Validasi request
    $request->validate([
        'user_id' => 'required|integer'
    ]);

    $user = auth()->user();

    // Jika bukan admin, pastikan user hanya bisa mengakses data miliknya sendiri
    if (!$user->hasRole('admin') && $user->id != $request->user_id) {
        return response(['message' => 'Unauthorized'], 403);
    }

    // Ambil akun berdasarkan user_id
    $account = accounts::where('user_id', $request->user_id)->first();

    if (!$account) {
        return response(['message' => 'Account not found'], 404);
    }

    // Ambil transaksi yang memiliki pinjaman
    $transactions = transactions::where('account_id', $account->id)
        ->whereHas('loan') // Hanya transaksi dengan pinjaman
        ->with(['loan', 'status']) // Eager load loan dan status
        ->get();

    if ($transactions->isEmpty()) {
        return response(['message' => 'No loans found'], 404);
    }

    return response(['message' => 'Loans retrieved successfully', 'loans' => $transactions], 200);
}


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'transaction_id' => 'required|integer'
        ]);

        

        $user = auth()->user();

        if($user->hasRole('admin')){
            $transaction = transactions::where('id', $request->transaction_id)->first();
            if(!$transaction){
                return response(['message' => 'Transaction not found'], 403);
            }else{
                return response(['message' => 'Transaction retrieved successfully', 'transaction' => $transaction], 200);
            }
        }


        if($request->user_id != auth()->user()->id){
            return response(['message' => 'Unauthorized'], 403);
        }

        $transaction = transactions::where('id', $request->transaction_id)->first();

        if(!$transaction){
            return response(['message' => 'Transaction not found'], 403);
        }else{
            return response(['message' => 'Transaction retrieved successfully', 'transaction' => $transaction], 200);
        }


        
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'transaction_id' => 'required|integer'
        ]);


        $user = auth()->user();

        if($user->hasRole('admin')){
            $transaction = transactions::where('id', $request->transaction_id)->first();
            if(!$transaction){
                return response(['message' => 'Transaction not found'], 403);
            }else{

                if($transaction->transaction_type == 'loan'){
                    $loan = loans::where('transaction_id', $transaction->id)->first();
                    $loan->delete();
                }
                $transaction->delete();
                return response(['message' => 'Transaction deleted successfully'], 200);
            }
        }

        if($request->user_id != auth()->user()->id){
            return response(['message' => 'Unauthorized'], 403);
        }

        $transaction = transactions::where('id', $request->transaction_id)->first();

        if(!$transaction){
            return response(['message' => 'Transaction not found'], 403);
        }else{
            if($transaction->transaction_type == 'loan'){
                $loan = loans::where('transaction_id', $transaction->id)->first();
                $loan->delete();
            }
            $transaction->delete();
            return response(['message' => 'Transaction deleted successfully'], 200);
        }

        
    }

    public function changeStatus (Request $request){
        $request->validate([
            'transaction_id' => 'required|integer',
            'status' => 'required|integer'
        ]);

        

        $user = auth()->user();

        if(!$user->hasRole('admin')){
            return response(['message' => 'Unauthorized'], 403);
        }

        $transaction = transactions::where('id', $request->transaction_id)->first();

        if(!$transaction){
            return response(['message' => 'Transaction not found'], 404);
        }

        $transaction->update([
            'status' => $request->status
        ]);

        return response(['message' => 'Transaction status updated successfully', 'transaction' => $transaction], 200);
    }
}
