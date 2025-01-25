<?php

namespace App\Entity;

use App\Repository\LinksRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinksRepository::class)]
class Links
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'links')]
    private ?Association $association = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

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
