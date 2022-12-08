<?php

namespace App\Http\Requests\Api\Analytics;

use App\Services\Analytics\Filters;
use Illuminate\Foundation\Http\FormRequest;

class GetAnalyticsRequest extends FormRequest implements Filters
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
        return [
            'endpoint' => 'string',
            'method' => 'string|in:GET,POST,PUT,PATCH,DELETE',
        ];
    }

    public function getPathCondition(): ?string
    {
        return $this->validated('endpoint');
    }

    public function getMethodCondition(): ?string
    {
        return $this->validated('method');
    }
}
