<?php

namespace App\Entity;

use App\Repository\SubjectEmailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubjectEmailRepository::class)]
class SubjectEmail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'subjectEmails')]
    private ?Association $association = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function __toString()
    {
        return $this->label;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): static
    {
        $this->association = $association;

        return $this;
    }
}
