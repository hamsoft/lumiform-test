<?php

namespace App\Collections;


use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

/**
 * @method Question find($key, $default = null)
 */
class QuestionCollection extends Collection
{

    public function getUuidsWhereRequiredAndUuidNotIn(array $answeredUuids): SupportCollection
    {
        return $this->where(Question::REQUIRED, '=', true)
            ->whereNotIn(Question::UUID, $answeredUuids)
            ->pluck(Question::UUID);
    }
}
