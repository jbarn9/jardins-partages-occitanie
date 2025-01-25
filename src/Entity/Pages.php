<?php

namespace App\Entity;

use App\Repository\PagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PagesRepository::class)]
class Pages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    /**
     * @var Collection<int, Tabs>
     */
    #[ORM\OneToMany(targetEntity: Tabs::class, mappedBy: 'pages')]
    private Collection $tabs_page;

    #[ORM\Column(length: 50)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?bool $home = null;

    public function __construct()
    {
        $this->tabs_page = new ArrayCollection();
    }
    public function __toString(): string
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
     * @return Collection<int, Tabs>
     */
    public function getTabsPage(): Collection
    {
        return $this->tabs_page;
    }

    public function addTabsPage(Tabs $tabsPage): static
    {
        if (!$this->tabs_page->contains($tabsPage)) {
            $this->tabs_page->add($tabsPage);
            $tabsPage->setPages($this);
        }

        return $this;
    }

    public function removeTabsPage(Tabs $tabsPage): static
    {
        if ($this->tabs_page->removeElement($tabsPage)) {
            // set the owning side to null (unless already changed)
            if ($tabsPage->getPages() === $this) {
                $tabsPage->setPages(null);
            }
        }

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

    public function isHome(): ?bool
    {
        return $this->home;
    }

    public function setHome(bool $home): static
    {
        $this->home = $home;

        return $this;
    }
}
