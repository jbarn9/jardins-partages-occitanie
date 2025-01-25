<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, Posts>
     */
    #[ORM\ManyToMany(targetEntity: Posts::class, inversedBy: 'categories')]
    private Collection $post_cat;

    public function __construct()
    {
        $this->post_cat = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

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

    /**
     * @return Collection<int, Posts>
     */
    public function getPostCat(): Collection
    {
        return $this->post_cat;
    }

    public function addPostCat(Posts $postCat): static
    {
        if (!$this->post_cat->contains($postCat)) {
            $this->post_cat->add($postCat);
        }

        return $this;
    }

    public function removePostCat(Posts $postCat): static
    {
        $this->post_cat->removeElement($postCat);

        return $this;
    }
}
