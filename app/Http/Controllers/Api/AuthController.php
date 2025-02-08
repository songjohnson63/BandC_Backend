<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    /**
     * Handle student login and return a token.
     */
    public function login(Request $request)
    {
        $customer = Customer::where('email', $request->email)->first();

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        if (!Hash::check($request->password, $student->password)) {
            return response()->json(['message' => 'Password mismatch'], 401);
        }

        // Debugging
        $token = $customer->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'customer' => $customer,
        ]);
    }


    /**
     * Handle student logout and revoke the token.
     */
    public function logout(Request $request)
    {
        // Revoke the current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
