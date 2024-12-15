<?php

namespace App\Http\Controllers;

use App\Models\loans;
use App\Models\accounts;
use Illuminate\Http\Request;
use App\Models\transactions;

class LoansController extends Controller
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

        $loan = loans::with('transaction.status')->get();

        return response(['message' => 'Loans retrieved successfully', 'loans' => $loan], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|integer'
        ]);

        

        $user = auth()->user();

        if($user->hasRole('admin')){
            $loans = loans::find($request->loan_id);
            if(!$loans){
                return response(['message' => 'Loan not found'], 403);
            }else{
                return response(['message' => 'Loan retrieved successfully', 'loan' => $loans], 200);
            }
        }

        $loans = loans::find($request->loan_id);
        if(!$loans){
            return response(['message' => 'Loan not found'], 403);
        }



        if($user->hasRole('user')){
            $account = accounts::where('user_id', $user->id)->first();
            $transaction = transactions::where('loan_id', $loans->transaction_id)->where('transaction_type', 'loan')->where('account_id', $account->id)->first();

            if(!$transaction){
                return response(['message' => 'Unauthorized'], 403);
            }else{
                return response(['message' => 'Loan retrieved successfully', 'loan' => $loans], 200);
            }
            
        }
    }


    public function update(Request $request){
        $request->validate([
            'loan_id' => 'required|integer',
            'interest_rate' => 'required|numeric',
            'loan_term' => 'required|integer',
            'monthly_installment' => 'required|numeric',
        ]);


        $loan = loans::find($request->loan_id);
        if(!$loan){
            return response(['message' => 'Loan not found'], 403);
        }

        $loan->update($request->all());

        return response(['message' => 'Loan updated successfully', 'loan' => $loan], 200);


    }
    

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'loan_id' => 'required|integer',
    //         'status' => 'required|string'
    //     ]);

    //     if($request->fails()){
    //         return response(['message' => $request->errors()->first()], 400);
    //     }

    //     $user = auth()->user();

    //     if($user->hasRole('admin')){
    //         $loans = loans::find($request->loan_id);
    //         if(!$loans){
    //             return response(['message' => 'Loan not found'], 403);
    //         }else{
    //             $loans->update($request->all());
    //             return response(['message' => 'Loan updated successfully', 'loan' => $loans], 200);
    //         }
    //     }else{
    //         if($user->hasRole('user')){
    //             $account = accounts::where('user_id', $user->id)->first();
    //             $transaction = transactions::where('loan_id', $request->loan_id)->where('transaction_type', 'loan')->where('account_id', $account->id)->first();
    //             if(!$transaction){
    //                 return response(['message' => 'Unauthorized'], 403);
    //             }else{
    //                 $loans = loans::find($request->loan_id);
    //                 if(!$loans){
    //                     return response(['message' => 'Loan not found'], 403);
    //                 }else{
    //                     $loans->update($request->all());
    //                     return response(['message' => 'Loan updated successfully', 'loan' => $loans], 200);
    //                 }
    //             }
    //         }
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|integer'
        ]);

        

        $user = auth()->user();

        $loan = loans::find($request->loan_id);

        if(!$loan){
            return response(['message' => 'Loan not found'], 403);
        }

        $loan->delete();

        return response(['message' => 'Loan deleted successfully'], 200);
    }

    
}
