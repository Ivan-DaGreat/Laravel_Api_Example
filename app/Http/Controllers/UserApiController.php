<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * UserApiController
 * - Handles all Api user request & modifications
 * - Auth Token Required
 *
 * @package App\Http\Controllers
 */
class UserApiController extends ApiController
{
	/**
	 * Get all Users
	 * @SEE: Response contains [id, name]
	 * @return JsonResponse
	 */
	public function index():JsonResponse
	{
		return response()->json([ 'users' => User::select(['id', 'name'])->get()], 200);
	}

	/**
	 * Get User By Id
	 * @param Request $request
	 * @param string $id Entity Id
	 * @SEE: Response contains [id, name, entry_id, entry_title, entry_slug]
	 * @return JsonResponse
	 */
	protected function show(Request $request, $id):JsonResponse
	{
		if (User::where('id', (int)$id)->exists()) {
			$user = User::select('users.id', 'users.name', 'entries.id AS entry_id', 'entries.title AS entry_title', 'entries.slug AS entry_slug')
				->where('users.id', (int)$id)
				->leftJoin('entries', 'entries.user_id', '=', 'users.id')
				->get();
			return response()->json($user, 200);
		}

		// Return 404 if we do not find the user
		return response()->json([
			"message" => "User not found"
		], 404);
	}

	/**
	 * Update the specified resource in storage.
	 * @TODO: What is a user allowed to change on their profile
	 * @param Request $request
	 * @param Int $id Entity Id
	 * @return JsonResponse
	 */
	public function update(Request $request, $id):JsonResponse
	{
		$user = User::find($id);
		if ($user->exists()) {
			// Data Validation
			$validator = Validator::make($request->all(), [
				'name' => 'required|string|min:3|max:25|unique:users|alpha_dash',
				// What can the user update????
			]);
			if ($validator->fails()) {
				return response()->json(['message' => $validator->errors()], 400);
			}

			// If all is well
			$user->name = $request->name;
			$user->save();
			return response()->json([
				"message" => "User Update Success"
			], 200);
		} else {
			// Did not find this user
			return response()->json([
				"message" => "User Not Found"
			], 404);
		}
	}
}
