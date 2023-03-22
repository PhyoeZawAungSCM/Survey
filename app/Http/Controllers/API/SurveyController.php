<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
	public function createSurvey(Request $request)
	{
		return response()->json($request);
	}
}
