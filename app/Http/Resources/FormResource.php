<?php

namespace App\Http\Resources;

use App\Models\Form;
use App\Models\Form\FormItem;
use App\Models\Question;
use App\Models\Section;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @mixin \App\Models\Form
 */
class FormResource extends JsonResource
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
        return [
            'checklist' => [
                'checklist_title' => $this->title,
                'checklist_description' => $this->description,
                'form' => [
                    'uuid' => $this->uuid,
                    'type' => Form::MODEL_TYPE,
                    'items' => $this->mapItems('')->toArray(),
                ],
            ],
        ];
    }

    /**
     * @param string $parentUuid
     *
     * @return \Illuminate\Support\Collection
     */
    protected function mapItems(string $parentUuid): Collection
    {
        return $this->items->where(FormItem::PARENT_UUID, $parentUuid)
            ->map(function (FormItem $formItem) {
                $itemResource = $this->makeItemResource($formItem);
                $items = $this->mapItems($formItem->element_uuid);

                if ($items->isNotEmpty()) {
                    $itemResource->additional([FormItemElementResource::ITEMS => $items->toArray()]);
                }
                return $itemResource;
            });
    }

    /**
     * @param \App\Models\Form\FormItem $formItem
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    function makeItemResource(FormItem $formItem): JsonResource
    {
        $formItem->load('element');

        $type = $formItem->element_type;
        $element = $formItem->element;

        if ($type === Question::MODEL_TYPE) {
            return new QuestionResource($element);
        }

        if ($type === Section::MODEL_TYPE) {
            return new SectionResource($element);
        }

        return new FormItemElementResource($element);
    }
}
