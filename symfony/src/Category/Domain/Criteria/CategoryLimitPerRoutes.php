<?php

namespace App\Category\Domain\Criteria;

enum CategoryLimitPerRoutes: int
{
    case PRINCIPAL_LIST_CATEGORIES = 10;
}
