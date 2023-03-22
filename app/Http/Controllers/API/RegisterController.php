<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
	public function register(RegisterRequest $registerRequest)
	{
		$validated = $registerRequest->validated();

		$user = User::create([
			'name'    => $validated['name'],
			'email'   => $validated['email'],
			'password'=> bcrypt($validated['password']),
		]);
        
		return response()->json([
			'message' => 'user register successfully',
			'user'    => $user,
		], 200);
	}
}
