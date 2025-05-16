<?php

namespace App\User\Application\UseCase\Response;

enum PublicUserRelation: int
{
    case YOURSELF = 0;
    case FRIENDS = 1;
    case STRANGERS = 2;
    case PENDING = 3;
    case WAITING = 4;
        // (por si luego me apetece hacer el bloqueo, aunq lo dudo JSJSJAJSJ)
    case BLOQUED = 5;
}
