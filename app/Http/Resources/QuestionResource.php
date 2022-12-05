<?php

namespace App\Http\Resources;

/**
 * @mixin \App\Models\Question
 */
class QuestionResource extends FormItemElementResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        $data = parent::toArray($request);

        $data['image_id'] = $this->image_id;
        $data['negative'] = $this->negative;
        $data['notes_allowed'] = $this->notes_allowed;
        $data['photos_allowed'] = $this->photos_allowed;
        $data['issues_allowed'] = $this->issues_allowed;
        $data['responded'] = $this->responded;
        $data['required'] = $this->required;
        $data['response_type'] = $this->response_type;

        return $data;
    }
}
