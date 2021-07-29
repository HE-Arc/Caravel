<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AuthAPIController extends BaseController
{
    /**
     * Method to handle LDAP user connexion
     */

    public function login(Request $request)
    {
        // This allow login with LDAP (mail or username) or fallback to internal login 
        $credentials = $request->only('mail', 'password');
        $credentials2 = [
            'sAMAccountName' => $credentials['mail'],
            'password' => $credentials['password'],
            'fallback' => [
                'email' => $request->mail,
                'password' => $request->password,
            ]
        ];

        if (Auth::attempt($credentials) || Auth::attempt($credentials2)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            $user->load('groupsAvailable');

            return [
                'user' => $user
            ];
        } else {
            return response()->json(['error' => "authentification failed."], 401);
        }
    }

    public function logout()
    {
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
