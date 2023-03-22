<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateSurveyRequest;
use App\Models\Question;
use App\Models\survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
	public function createSurvey(CreateSurveyRequest $createSurveyRequest)
	{
		$survey = DB::transaction(function () use ($createSurveyRequest) {
			$survey = survey::create([
				'title'         => $createSurveyRequest->title,
				'description'   => $createSurveyRequest->description,
				'status'        => $createSurveyRequest->status,
				'create_user_id'=> $createSurveyRequest->user()->id,
				'expire_date'   => $createSurveyRequest->expire_date,
				'image'         => 'hahaha'
			]);
			foreach ($createSurveyRequest->questions as $question) {
				$question['survey_id'] = $survey->id;
				$validator = Validator::make($question, [
					'title'      => 'required|string',
					'description'=> 'string|nullable',
					'type'       => 'required|string',
					'data'       => 'nullable',
					'survey_id'  => 'required|integer'
				]);
				$validated = $validator->validated();
				$this->storeQuestion($validated);
			}
			return $survey;
		});

		return response()->json([
			'message'=> 'survey create success',
			'survey' => $survey
		]);
	}

	private function storeQuestion($validated)
	{
		Question::create([
			'title'      => $validated['title'],
			'description'=> $validated['description'],
			'survey_id'  => $validated['survey_id'],
			'type'       => $validated['type'],
			'data'       => $validated['data'] ? json_encode($validated['data']) : null
		]);
	}

	public function getSurveys(Request $request)
	{
		$user = $request->user();
		$surveys = survey::where('create_user_id', $user->id)->get();

		return response()->json([
			'message' => 'retriving message success',
			'surveys' => $surveys,
		]);
	}

	public function getSurvey($id)
	{
		$survey = survey::find($id);
		$questions = survey::find($id)->question;
		$survey['questions'] = $questions;
		foreach ($survey['questions'] as $question) {
			$question['data'] = json_decode($question['data']);
		}
		return response()->json([
			'survey' => $survey
		], 200);
	}

	public function updateSurvey($id, Request $request)
	{
		$survey = survey::find($id);
        $questions = survey::find($id)->question;
		$survey::update([
			'title'       => $request->title,
			'description' => $request->description,
			'status'      => $request->status,
			'expire_date' => $request->expire_date,
			'image'       => 'hahaha',
		]);

        $request_question = $request->questions; 
	}
}
