<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Form\FormItemElement
 */
class FormItemElementResource extends JsonResource
{
    const ITEMS = 'items';

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'uuid' => $this->getUuid(),
            'type' => $this->getElementType(),
            'title' => $this->getTitle(),
        ];

        if (isset($this->additional[self::ITEMS])) {
            $data[self::ITEMS] = $this->additional[self::ITEMS];
        }

        return $data;
    }
}
