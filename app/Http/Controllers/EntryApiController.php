<?php

namespace App\Http\Controllers;

use App\Entries;
use Auth;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EntryApiController extends ApiController
{
    /**
     * Get all entries
     * @return JsonResponse
     */
    public function index()
    {
	    return response()->json([ 'entries' => Entries::select(['id', 'title'])->get()], 200);
    }

    /**
     * Store a new entry
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request):JsonResponse
    {
    	// Data Validation
	    $validator = Validator::make($request->all(), [
		    'title' => 'required|string|min:5|max:255',
		    'slug' => 'required|unique:App\entries,slug|max:255',
		    'body' => 'required',
		    'type_id' => 'required|numeric',
	    ]);
	    if ($validator->fails()) {
		    return response()->json(['message' => $validator->errors()], 400);
	    }
	    $newEntry = $request->all();
	    $newEntry['user_id'] = Auth::user()->id;

	    // Create/Save New Entry
	    $entry = Entries::create($newEntry);

	    $entry->save();
	    return response()->json([
		    "message" => "New Entry Created Successfully"
	    ], 200);
    }

    /**
     * Get a single entry
     * @param Request $request
     * @param int  $id
     * @return JsonResponse
     */
    public function show(Request $request, $id):JsonResponse
    {
	    if (Entries::where('id', (int)$id)->exists()) {
		    $entry = Entries::select('entries.id', 'entries.title', 'entries.slug', 'users.name AS owner')
			    ->where('entries.id', (int)$id)
			    ->leftJoin('users', 'entries.user_id', '=', 'users.id')
			    ->get();
		    return response()->json($entry, 200);
	    }

	    // Return 404 if we do not find the user
	    return response()->json([
		    "message" => "Entry not found"
	    ], 404);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id):JsonResponse
    {
	    $entry = Entries::find($id);
	    if ($entry->exists()) {
		    // Validate New Data
		    $validator = Validator::make($request->all(), [
			    'title' => 'required|string|min:5|max:255',
			    'slug' => 'required|max:255',
			    'body' => 'required',
			    'type_id' => 'required|numeric',
		    ]);
		    if ($validator->fails()) {
			    return response()->json(['message' => $validator->errors()], 400);
		    }

		    $entry->title = $request->title;
		    $entry->slug = $request->slug;
		    $entry->body = $request->body;
		    $entry->type_id = $request->type_id;
		    $entry->save();
		    return response()->json([
			    "message" => "Entry Updated"
		    ], 200);
	    } else {
		    // Did not find the entry
		    return response()->json([
			    "message" => "Entry Not Found"
		    ], 404);
	    }
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id):JsonResponse
    {
	    if (Entries::where('id', (int)$id)->exists()) {
		    // Remove
		    $entry = Entries::find($id);
		    $entry->delete();
		    return response()->json([
			    "message" => "Entry Removed"
		    ], 200);
	    }

	    // redirect
	    return response()->json([
		    "message" => "Entry not found"
	    ], 404);
    }
}
