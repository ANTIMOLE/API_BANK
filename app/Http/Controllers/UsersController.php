<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use App\Models\Accounts;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if(!$user->hasRole('admin')){
            return response(['message' => 'Unauthorized'], 403);
        }

        $users = User::all();

        return response(['message' => 'Users retrieved successfully', 'users' => $users], 200);
    }
    public function getData(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        

        
        $user = auth()->user();

        if(!$user->hasRole('admin')){
            return response(['message' => 'Unauthorized'], 403);
        }

        $user = User::find($request->user_id);

        if(!$user){
            return response(['message' => 'User not found'], 403);
        }else{
            $account = Accounts::where('user_id', $user->id)->first();
            if(!$account){
                return response(['message' => 'Account not found'], 403);
            }else{
                return response(['message' => 'User data retrieved successfully', 'user' => $user, 'account' => $account], 200);
            }
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
{
    // Option 1: Using Validator facade
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:8',
        'phone' => 'required|string',
        'gender' => 'required|string'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors()->first()
        ], 400);
    }

    // Option 2: Alternative approach using $this->validate()
    // $validatedData = $this->validate($request, [
    //     'name' => 'required|string',
    //     'email' => 'required|email|unique:users',
    //     'password' => 'required|string|min:8',
    //     'phone' => 'required|string',
    //     'gender' => 'required|string'
    // ]);

    $request['password'] = bcrypt($request->password);

    $user = User::create($request->all());

    $user->assignRole('user');

    $account = Accounts::create([
        'user_id' => $user->id,
        'account_number' => rand(00000, 99999),
        'balance' => 0
    ]);

    return response()->json([
        'message' => 'User created successfully', 
        'user' => $user, 
        'account' => $account
    ], 200);
}

    /**
     * Display the specified resource.
     */
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string'
    ]);

    if (!auth()->attempt($request->only('email', 'password'))) {
        return response(['message' => 'Invalid credentials'], 401);
    }

    $user = Auth::user();

    $tokenResult = $user->createToken('UserToken', ['*']);
    $plainTextToken = $tokenResult->plainTextToken;
    $accessToken = $tokenResult->accessToken;

    $accessToken->expires_at = now()->addDays(120);
    $accessToken->save();

    $accounts = accounts::where('user_id',$user->id)->first();

    return response([
        'message' => $user->hasRole('admin') ? 'Admin logged in successfully' : 'User logged in successfully',
        'user' => $user,
        'accounts' => $accounts,
        'token' => $plainTextToken,
        'tokenData' => [
            'id' => $accessToken->id,
            'name' => $accessToken->name,
            'abilities' => $accessToken->abilities,
            'expires_at' => $accessToken->expires_at,
            'created_at' => $accessToken->created_at,
        ]
    ], 200);
}



    // public function ReLogin(Request $request)
    // {
    //     $token = $request->header('Authorization'); // Expecting token in the Authorization header
        
        
    //     if (!$token) {
    //         return response()->json(['message' => 'No token provided'], 401);
    //     }

        
    //     $token = str_replace('Bearer ', '', $token);

    //     try {
            
    //         $user = Auth::guard('api')->setToken($token)->user(); 

            
    //         $expirationTime = Carbon::parse($user->token()->expires_at);

    //         if ($expirationTime->isPast()) {
    //             if ($request->has('remember_me') && $request->remember_me == true) {
                    
    //                 $newToken = $user->createToken('NewAccessToken');
    //                 return response()->json(['message' => 'Token refreshed', 'token' => $newToken], 200);
    //             } else {
                    
    //                 return response()->json(['message' => 'Token expired. Please log in again.'], 401);
    //             }
    //         }

            
    //         return response()->json(['message' => 'You are still logged in'], 200);

    //     } catch (\Exception $e) {
            
    //         return response()->json(['message' => 'Invalid or expired token'], 401);
    //     }
    // }   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
            'gender' => 'required|string'
        ]);

    

        $user = User::where('email', $request->email)->first();

       
            
        $user = User::find($user->id);
        $request['password'] = bcrypt($request['password']);
        $user->update($request->except('email'));

        return response(['message' => 'User updated successfully', 'user' => $user], 200);
            
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

      

        $user = Auth::user();

        if(!$user){
            return response(['message' => 'Unauthorized'], 403);
        }

        if($user->hasRole('admin')){
            $user = User::find($request->user_id);
            $user->delete();
        }else{
            if($user->id != $request->user_id){
                return response(['message' => 'Unauthorized'], 403);
            }else{
                $user = User::find($user->id);
                $user->delete();
                return response(['message' => 'User deleted successfully'], 200);
            }
        }
    }
}
