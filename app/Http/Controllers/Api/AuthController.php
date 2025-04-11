<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller; // Fix incorrect namespace
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Handle customer login and return a token.
     */
    public function login(Request $request)
    {
        $customer = Customer::where('phonenumber', $request->phonenumber)->first();

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        // Debugging: Check if password exists
        if (!$customer->password) {
            return response()->json(['message' => 'Password is missing in database'], 500);
        }

        // Debugging: Check password match
        if (!Hash::check($request->password, $customer->password)) {
            return response()->json([
                'message' => 'Password mismatch',
                'input_password' => $request->password,
                'stored_hash' => $customer->password,
                'hash_check' => Hash::check($request->password, $customer->password), // Should be true if matching
            ], 401);
        }

        // Create token if password is correct
        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'customer' => $customer,
        ]);
    }


    /**
     * Handle customer logout and revoke the token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
