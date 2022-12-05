<?php

namespace App\Http\Requests\Api\Form;

use App\Models\Form;
use App\Services\Form\FormItemService;
use App\Services\FormService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class NewFormRequest extends FormRequest
{
    private mixed $availableItemTypes = [];
    /**
     * @var \App\Services\Form\FormItemService
     */
    private FormItemService $formItemService;


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
     * @return array<string, mixed>
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function rules(): array
    {
        $formService = app()->make(FormService::class);
        $this->formItemService = app()->make(FormItemService::class);
        $this->availableItemTypes = Form\FormItem::getAvailableTypes();

        $rules = new Collection($formService->getDefaultValidationRules());

        $this->addRulesForNestedItems($rules, 'items', $this->get('items', []));

        return $rules->toArray();
    }

    /**
     * Add rules for nested items
     *
     * @param \Illuminate\Support\Collection $rules
     * @param string $key
     * @param array $items
     *
     * @return void
     */
    private function addRulesForNestedItems(Collection $rules, string $key, array $items): void
    {
        if (empty($items)) {
            return;
        }

        $rules->put($key . '.*.type', [
            'required',
            'string',
            Rule::in($this->availableItemTypes),
        ]);

        $this->attributes->set($key . '.*.type', 'type');

        foreach ($items as $position => $value) {
            $type = $value['type'] ?? '';

            if (!is_string($type) || !in_array($type, $this->availableItemTypes)) {
                continue;
            }

            $this->addTypeItemRules($rules, $key . '.' . $position, $type);

            $this->addRulesForNestedItems($rules, $key . '.' . $position . '.items', $value['items'] ?? []);
        }
    }

    public function addTypeItemRules(Collection $rules, $key, $type)
    {
        $itemRules = $this->formItemService->getElementServiceByType($type)->getDefaultValidationRules();

        foreach ($itemRules as $field => $fieldRules) {
            $rules->put($key . '.' . $field, $fieldRules);
            $this->attributes->set($key . '.' . $field, $field);
        }
    }

    public function attributes(): array
    {
        return $this->attributes->all();
    }
}
