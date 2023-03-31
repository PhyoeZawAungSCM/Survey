<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionAnswerResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return [
			'id'                  => $this->id,
			'survey_id'           => $this->survey_id,
			'created_at'          => $this->created_at,
			'updated_at'          => $this->updated_at,
			'data'     => json_decode($this->data)
		];
	}
}
