<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyRequest extends FormRequest
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
			'title'                  => 'required|string',
			'description'            => 'required|string',
			'status'                 => 'required|boolean',
			'expire_date'            => 'required|date',
			'questions'              => 'required|array',
			'questions.*.title'      => 'required|string',
			'questions.*.description'=> 'nullable|string',
			'questions.*.data'       => 'nullable|array',
			'questions.*.type'       => 'string|required',
		];
	}
}
