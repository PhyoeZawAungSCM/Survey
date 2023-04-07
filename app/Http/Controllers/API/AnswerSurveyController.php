<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SubmitAnswerRequest;
use App\Http\Resources\SurveyResource;
use App\Models\Answer;
use App\Models\QuestionAnswer;
use App\Models\survey;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class AnswerSurveyController extends Controller
{
	public function getSurveyBySlug(survey $survey)
	{
		$currentDate = Carbon::now();
		if ($survey->expire_date < $currentDate) {
			return response()->json([
				'message'=> 'Expire'
			], 422);
		}
		if ($survey->status == 0) {
			return response()->json([
				'message'=> 'Survey is not active'
			], 422);
		}
		return new	SurveyResource($survey);
	}

	public function storeAnswer(SubmitAnswerRequest $request, survey $survey)
	{
		DB::beginTransaction();
		try {
			$answer = Answer::create([
				'survey_id'=> $survey->id,
				'name'     => $request->name,
			]);
			foreach ($request->answers as $answerReq) {
				QuestionAnswer::create([
					'question_id'=> $answerReq['id'],
					'answer_id'  => $answer->id,
					'data'       => json_encode($answerReq['data'])
				]);
			}
		} catch(Throwable $th) {
			DB::rollBack();
			return response()->json([
				'message'=> 'some error occur',
				'error'  => $th->getMessage(),
			], 500);
		}
		DB::commit();
		return response()->json([
			'message'=> 'submit answer success',
		], 200);
	}
}
