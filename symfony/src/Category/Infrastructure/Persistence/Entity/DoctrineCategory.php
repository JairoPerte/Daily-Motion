<?php

namespace App\Category\Infrastructure\Persistence\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: "category")]
class DoctrineCategory
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: Types::STRING)]
    public string $id;

    #[ORM\Column(name: 'user_id', type: Types::STRING)]
    public string $userId;

    #[ORM\Column(name: 'icon', type: Types::INTEGER)]
    public int $iconNumber;

    #[ORM\Column(name: 'name', type: Types::STRING)]
    public string $name;
}
