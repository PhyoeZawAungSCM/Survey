<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAnswerRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'survey_id'     => 'required|integer|exists:surveys,id',
			'answers'       => 'required|array',
			'name'          => 'required|string',
			'answers.*.id'  => 'required|integer|exists:questions,id',
			'answers.*.data'=> 'required'
		];
	}
}
