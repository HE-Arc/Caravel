<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AuthAPIController extends BaseController
{
    /**
     * Method to handle user json token auth
     */

    public function login(Request $request) {
        $credentials = $request->only('mail', 'password');
    
        if (Auth::attempt($credentials)) {
            /** @var User $user */
            $user = Auth::user();
            
            $token = $user->createToken("token");
    
            return ['token' => $token->plainTextToken,
                    'user' => $user];
        } else {
            return response()->json(['error' => "authentification failed."], 401);
        }
    }

    public function logout() {
        // Get user who requested the logout
        $user = request()->user(); //or Auth::user()
        // Revoke current user token
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        /** @var Guard $guard */
        $guard = Auth::guard('web');
        $guard->logout();

        return ["message" => "successfully logged out"];
    }
}