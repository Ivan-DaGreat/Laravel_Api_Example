<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * This Class could handle all authentications
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
	/**
	 * To access our Api Users will need to login first
	 * POST: email, password
	 * @param Request $request
	 * @return JsonResponse accessToken
	 */
	public function apiLogin(Request $request):JsonResponse
	{
		// Prepare our credentials
		$credentials = $request->validate([
			'email' => 'email|required',
			'password' => 'required'
		]);

		// Authenticate....
		if (!auth()->attempt($credentials)) {
			// If the attempt failed send a nice rejection
			return response()->json(['message' => 'Invalid Credentials'], 403);
		}

		return response()->json([
			'user' => auth()->user(),
			'access_token' => auth()->user()->createToken('authToken')->accessToken
		]);
	}
}
