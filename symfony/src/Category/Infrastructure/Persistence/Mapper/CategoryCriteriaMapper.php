<?php

namespace App\Category\Infrastructure\Persistence\Mapper;

use App\Category\Domain\Criteria\CategoryCriteria;

class CategoryCriteriaMapper
{
    public function toArray(CategoryCriteria $criteria): array
    {
        return array_filter(
            [
                "name" => $criteria->name,
                "iconNumber" => $criteria->iconNumber
            ],
            fn($value) => $value
        );
    }
}
