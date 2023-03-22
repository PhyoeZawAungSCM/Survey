<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
	public function login(LoginRequest $loginRequest)
	{
		$validated = $loginRequest->validated();
		if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $remember = $loginRequest['remember_me'])) {
			return response()->json([
				'message'=> 'provided credentials does not match'
			], 401);
		}
		$token = Auth::user()->createToken('user_token');
		return response()->json([
			'message' => 'login success',
			'user'    => Auth::user(),
			'token'   => $token->plainTextToken
		]);
	}
}
