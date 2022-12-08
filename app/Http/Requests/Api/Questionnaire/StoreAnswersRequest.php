<?php

namespace App\Http\Requests\Api\Questionnaire;

use App\Models\Answer;
use App\Models\Form;
use Illuminate\Foundation\Http\FormRequest;

class StoreAnswersRequest extends FormRequest
{
    public const ANSWERS = 'answers';

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
        $questionUuidKey = self::ANSWERS . '.*.' . Answer::QUESTION_UUID;
        $this->attributes->set($questionUuidKey, 'question_uuid');

        return [
            Form::UUID => 'bail|required|string|exists:' . Form::TABLE . ',' . Form::UUID,
            self::ANSWERS => 'required|array',
            $questionUuidKey => 'required|string',
        ];
    }

    public function getFormUuid(): string
    {
        return $this->get(Form::UUID);
    }

    public function attributes(): array
    {
        return $this->attributes->all();
    }
}
