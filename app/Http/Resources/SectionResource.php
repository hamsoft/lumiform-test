<?php

namespace App\Http\Resources;

/**
 * @mixin \App\Models\Section
 */
class SectionResource extends FormItemElementResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $data['repeat'] = $this->repeat;
        $data['weight'] = $this->weight;
        $data['required'] = $this->required;

        return $data;
    }
}
