<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PivotResource extends JsonResource
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
                "question_id"=>$this->question_id,
                "answer_id"=>$this->answer_id,
                "data"=>json_decode($this->data),
        ];
    }
}
