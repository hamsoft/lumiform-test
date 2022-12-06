<?php

namespace App\Http\Requests\Api\Questionnaire;

use App\Collections\QuestionCollection;
use App\Models\Form;
use Illuminate\Foundation\Http\FormRequest;

class StoreAnswersRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $this->attributes->set('answers.*.question_uuid', 'question_uuid');

        $this->validate([
            'uuid' => 'bail|required|string|exists:' . Form::TABLE . ',' . Form::UUID,
            'answers' => 'required|array',
        ]);

        $rules = [];

        $key = 'answers';
        foreach ($this->get($key) as $index => $answer) {
            $position = $key . '.' . $index;

            $field = 'question_uuid';
            if (empty($answer[$field])) {
                $rules[$position . '.' . $field] = 'required|string';
                $this->attributes->set($position . '.' . $field, 'question_uuid');
            }
        }

        return $rules;
    }

    public function getFormUuid(): string
    {
        return $this->get('uuid');
    }

    public function getAnswers(): array
    {
        return $this->get('answers', []);
    }

    public function attributes(): array
    {
        return $this->attributes->all();
    }
}
