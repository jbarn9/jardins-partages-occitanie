<?php

namespace App\Entity;

use App\Repository\TabsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: TabsRepository::class)]
class Tabs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column]
    private ?bool $news_feed = null;

    /**
     * @var Collection<int, Posts>
     */
    #[ORM\OneToMany(targetEntity: Posts::class, mappedBy: 'tab', cascade: ['remove'])]
    private Collection $tabs_posts;

    #[ORM\ManyToOne(inversedBy: 'tabs_page')]
    private ?Pages $pages = null;

    #[ORM\Column(length: 50, unique: true, options: ['message' => 'Ce slug est déjà utilisé'])]
    private ?string $slug = null;

    public function __construct()
    {
        $this->tabs_posts = new ArrayCollection();
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

    public function isNewsFeed(): ?bool
    {
        return $this->news_feed;
    }

    public function setNewsFeed(bool $news_feed): static
    {
        $this->news_feed = $news_feed;

        return $this;
    }

    /**
     * @return Collection<int, Posts>
     */
    public function getTabsPosts(): Collection
    {
        return $this->tabs_posts;
    }

    public function addTabsPost(Posts $tabsPost): static
    {
        if (!$this->tabs_posts->contains($tabsPost)) {
            $this->tabs_posts->add($tabsPost);
            $tabsPost->setTab($this);
        }

        return $this;
    }

    public function removeTabsPost(Posts $tabsPost): static
    {
        if ($this->tabs_posts->removeElement($tabsPost)) {
            // set the owning side to null (unless already changed)
            if ($tabsPost->getTab() === $this) {
                $tabsPost->setTab(null);
            }
        }
        return $this;
    }

    public function getPages(): ?Pages
    {
        return $this->pages;
    }

    public function setPages(?Pages $pages): static
    {
        $this->pages = $pages;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
