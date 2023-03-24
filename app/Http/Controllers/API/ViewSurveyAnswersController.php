<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
		$result = DB::table('answers')
			->join('surveys', 'surveys.id', '=', 'answers.survey_id')
			->where('create_user_id', '=', $user->id)
			->get();

		return response()->json([
			'latest_survey'  => $latestSurvey,
			'survey_count'   => $surveyCount,
			'answer_count'   => $answerCount,
			'latest_answers' => $result,
		]);
	}

    public function getQuestionAndAnswer(Request $request,Answer $answer){
        
        // $haha = DB::table('question_answers')
        // ->join('questions','questions.id','=','question_answers.question_id')
        // ->join('answers','answers.id','=','question_answers.answer_id')
        // ->join('surveys','surveys.id','=','answers.survey_id')
        // ->get(['questions.id as question_id',
        // 'answers.id as answer_id',
        // 'question_answers.id as question_answer_id',
        // 'question_answers.data as answer_data',
        // 'questions.data as question_data',
        // 'questions.title as question_title',
        // 'questions.description as question_description',
        // 'surveys.*']);
        $haha = $answer->questions;
        return AnswerResource::collection($haha);
        // return response()->json([
        //     'data'=>$haha
        // ]);
    //    if($user->id != $answer->survey->create_user_id){
    //     return response()->json([
    //         'message' => 'Unauthorize action'
    //     ],422);
      //  }
    }
}
