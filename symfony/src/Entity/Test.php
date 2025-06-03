<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Test
{
    #[ORM\Id]
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $idRepName = null;
}
