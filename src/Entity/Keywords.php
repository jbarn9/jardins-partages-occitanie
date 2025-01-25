<?php

namespace App\Entity;

use App\Repository\KeywordsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KeywordsRepository::class)]
class Keywords
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    /**
     * @var Collection<int, Posts>
     */
    #[ORM\ManyToMany(targetEntity: Posts::class, mappedBy: 'keywords')]
    private Collection $keywords_posts;

    public function __construct()
    {
        $this->keywords_posts = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->label;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Posts>
     */
    public function getKeywordsPosts(): Collection
    {
        return $this->keywords_posts;
    }

    public function addKeywordsPost(Posts $keywordsPost): static
    {
        if (!$this->keywords_posts->contains($keywordsPost)) {
            $this->keywords_posts->add($keywordsPost);
            $keywordsPost->addKeyword($this);
        }

        return $this;
    }

    public function removeKeywordsPost(Posts $keywordsPost): static
    {
        if ($this->keywords_posts->removeElement($keywordsPost)) {
            $keywordsPost->removeKeyword($this);
        }

        return $this;
    }
}
