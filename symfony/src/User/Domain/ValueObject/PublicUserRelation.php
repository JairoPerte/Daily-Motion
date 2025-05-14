<?php

namespace App\User\Domain\ValueObject;

enum PublicUserRelation: int
{
    case YOURSELF = 0;
    case FRIENDS = 1;
    case STRANGERS = 2;
        // (por si luego me apetece hacer el bloqueo, aunq lo dudo JSJSJAJSJ)
    case BLOQUED = 3;
}
