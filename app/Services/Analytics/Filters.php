<?php

namespace App\Services\Analytics;

interface Filters
{

    public function getMethodCondition(): ?string;

    public function getPathCondition(): ?string;
}
