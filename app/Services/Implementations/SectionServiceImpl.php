<?php

namespace App\Services\Implementations;

use App\Models\Section;
use App\Services\SectionService as SectionServiceInterface;

class SectionServiceImpl implements SectionServiceInterface
{
    public function findByUuidOrCreate(iterable $data): Section
    {
        $section = Section::query()->findOrNew($data[Section::UUID] ?? null);

        $section->fill((array)$data)->save();

        return $section;
    }

    public function getDefaultValidationRules(): array
    {
        return [
            Section::TITLE => 'required|string',
            Section::WEIGHT => 'required|integer',
            Section::REQUIRED => 'required|boolean',
            Section::REPEAT => 'required|boolean',
        ];
    }
}
