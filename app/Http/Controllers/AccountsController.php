<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use Illuminate\Http\Request;
use App\Models\User;

class AccountsController extends Controller
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

        $accounts = accounts::all();

        return response(['message' => 'Accounts retrieved successfully', 'accounts' => $accounts], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'account_number' => 'required|string',
            'balance' => 'required|numeric'
        ]);

        

        $user = auth()->user();

        if(!$user->hasRole('admin')){
            return response(['message' => 'Unauthorized'], 403);
        }

        $account = accounts::create([
            'user_id' => $request->user_id,
            'account_number' => $request->account_number,
            'balance' => $request->balance
        ]);

        return response(['message' => 'Account created successfully', 'account' => $account], 201);


    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'account_number' => 'required|integer'
        ]);

        

        $user = auth()->user();
        if($user->hasRole('admin')){
                $account = accounts::where('user_id', $request->user_id)->where('id', $request->account_id)->first();
                if(!$account){
                    return response(['message' => 'Account not found'], 404);
                }else{
                    return response(['message' => 'Account retrieved successfully', 'account' => $account], 200);
                }
        }else{
            if($user->user_id != $request->user_id){
                return response(['message' => 'Unauthorized'], 403);
            }else{
                $account = accounts::where('user_id', $request->user_id)->where('id', $request->account_id)->first();
                if(!$account){
                    return response(['message' => 'Account not found'], 404);
                }else{
                    return response(['message' => 'Account retrieved successfully', 'account' => $account], 200);
                }
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'account_number' => 'required|integer',
            'balance' => 'required|numeric'
        ]);

       

        $user = auth()->user();

        if($user->hasRole('admin')){
            $account = accounts::where('account_number', $request->account_number)->first();
            if(!$account){
                return response(['message' => 'Account not found'], 404);
            }else{
                $account->update([
                    'balance' => $request->balance
                ]);
                return response(['message' => 'Account updated successfully', 'account' => $account], 200);
            }
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'account_number' => 'required|integer'
        ]);

        

        $user = auth()->user();

        if($user->hasRole('admin')){
            $account = accounts::where('account_number', $request->account_number)->first();
            if(!$account){
                return response(['message' => 'Account not found'], 404);
            }else{
                $userdel = $account->user_id;

                $userdel = User::where('id', $userdel)->first();
                $userdel->delete();
                $account->delete();
                return response(['message' => 'Account and User deleted successfully'], 200);
            }
        }
    }
}
