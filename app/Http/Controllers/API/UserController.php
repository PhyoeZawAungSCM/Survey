<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
	public function getUser(Request $request)
	{
		$user = $request->user();

		return response()->json([
			'message' => 'getting user data success',
			'user'    => $user,
			'token'   => $request->bearerToken()
		], 200);
	}

	public function logout(Request $request)
	{
		$request->user()->tokens()->delete();
		return response()->json([
			'message' => 'logout success',
		]);
	}
}
