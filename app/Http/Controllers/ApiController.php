<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class ApiController extends Controller
{
	/**
	 * DB Table to perform actions against
	 * @var string
	 */
	protected $dbTable = null;

	/**
	 * Fields that this table will updated
	 * @var array
	 */
	protected $fillable = [];

	########### Here we are forcing the children to extend these methods ############
	/**
	 * Get A Single Entity By Id
	 * @param Request $request
	 * @param Int $id Entity Id
	 * @return JsonResponse
	 */
	abstract protected function show(Request $request, $id):JsonResponse;

	/**
	 * Update
	 * @param Request $request
	 * @param Int $id Entity Id
	 * @return JsonResponse
	 */
	abstract protected function update(Request $request, $id):JsonResponse;
}
