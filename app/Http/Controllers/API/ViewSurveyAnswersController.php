<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerDetailResource;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\QuestitonResource;
use App\Models\Answer;
use App\Models\QuestionAnswer;
use App\Models\survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewSurveyAnswersController extends Controller
{
	public function getDashboardData(Request $request)
	{
		$surveys = $request->user()->survey();
		$surveyCount = $surveys->count();
		$latestSurvey = $surveys->latest('created_at')->first();
		$answerCount = 0;
		$surveys = $request->user()->survey;
		foreach ($surveys as $survey) {
			$answerCount += $survey->answer()->count();
		}
		$user = $request->user();
		$latestAnswer = DB::table('answers')
			->join('surveys', 'surveys.id', '=', 'answers.survey_id')
			->where('create_user_id', '=', $user->id)
	  ->latest('answers.created_at')
	  ->take(5)
			->get();

		return response()->json([
			'latest_survey'  => $latestSurvey,
			'survey_count'   => $surveyCount,
			'answer_count'   => $answerCount,
			'latest_answers' => $latestAnswer,
		]);
	}

	public function getQuestionAndAnswer(Request $request, Answer $answer)
	{
		$user = $request->user();
		$surveys = survey::where('create_user_id', $user->id)->get();
		foreach ($surveys as $survey) {
			$survey['answers'] = Answer::where('survey_id', $survey->id)->get();
		}
		return response()->json([
			'surveys' => $surveys,
		]);
	}

	public function getSurveyWithAnswerId($id)
	{
		$survey = Answer::find($id)->survey;
		$answer_detail = new AnswerDetailResource(Answer::find($id));
		$questions = QuestitonResource::collection($survey->question);
		$answers = [];
		foreach ($questions as $question) {
			$answer = QuestionAnswer::where('question_id', '=', $question['id'])
			->where('answer_id', '=', $id)
			->get()[0];
			$answer['qeustion_id'] = $question['id'];
			array_push(
				$answers,
				new AnswerResource($answer)
			);
		}
		return response()->json([
			'questions'=> $questions,
			'answers'  => $answers,
			'answer_detail' => $answer_detail,
		]);
	}
}
