<?php

namespace App\Entity;

use App\Repository\PicturesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PicturesRepository::class)]
#[ORM\Table(name: 'Pictures')]
class Pictures
{
    #[ORM\Column(length: 50)]
    private string $name;

    #[ORM\Column(length: 50)]
    private string $idRepName;

    #[ORM\Column(length: 50)]
    private string $tableName;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getIdRepName(): string
    {
        return $this->idRepName;
    }

    public function setIdRepName(string $idRepName): self
    {
        $this->idRepName = $idRepName;
        return $this;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function setTableName(string $tableName): self
    {
        $this->tableName = $tableName;
        return $this;
    }
} 