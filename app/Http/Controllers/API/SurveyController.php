<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateSurveyRequest;
use App\Http\Requests\API\SubmitAnswerRequest;
use App\Http\Requests\API\UpdateSurveyRequest;
use App\Http\Resources\SurveyResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

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
				self::storeQuestion($validated);
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
		$surveys = $request->user()->survey;
		return response()->json([
			'message' => 'retriving message success',
			'surveys' => $surveys,
		]);
	}

	public function getSurvey($id)
	{
		$survey = survey::find($id);
		return new SurveyResource($survey);
	}

	public function updateSurvey($id, UpdateSurveyRequest $request)
	{
		$survey = survey::find($id);
		$questions = survey::find($id)->question()->pluck('id');
		$requestQuestions = collect($request->questions)->pluck('id');
		$toAdd = $requestQuestions->diff($questions);
		$toDelete = $questions->diff($requestQuestions);
		$toUpdate = $questions->diff($toDelete);
		DB::beginTransaction();
		try {
			$survey->update([
				'title'       => $request->title,
				'description' => $request->description,
				'status'      => $request->status,
				'expire_date' => $request->expire_date,
			]);
			foreach ($toDelete as $delete) {
				Log::info(print_r('delete' . $delete, true));
				Question::find($delete)->delete();
			}
			foreach ($toAdd as $add) {
				foreach ($request->questions as $question) {
					if ($question['id'] == $add) {
						$question['survey_id'] = $id;
						$this->storeQuestion($question);
					}
				}
			}

			foreach ($toUpdate as $update) {
				foreach ($request->questions as $question) {
					if ($question['id'] == $update) {
						Question::find($update)->update($question);
					}
				}
			}
		} catch(Throwable $th) {
			DB::rollBack();
			return response()->json([
				'message' => 'some error occur in updating survey',
				'error'   => $th->getMessage(),
			]);
		}
		DB::commit();
		return response()->json([
			'message' => 'update survey success',
			'survey'  => $survey
		]);
	}

	public function deleteSurvey($id)
	{
		survey::find($id)->question()->delete();
		$survey = survey::find($id);
		$survey->delete();
		return response()->json([
			'message' => 'deleteSuccess',
			'survey'  => $survey
		]);
	}

	
}
