<?php

namespace Database\Factories\Form;

use App\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FormItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
        ];
    }

    public function createFormItems(Form $form, array $itemsData, Form\FormItemElement $parent)
    {
        foreach ($itemsData as $item) {
            $type = $item['type'];
            $element = $this->createElement($type);

        }
    }

}
