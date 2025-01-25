<?php

namespace App\Entity;

use App\Repository\CitiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CitiesRepository::class)]
class Cities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 10, nullable: false)]
    #[Assert\Regex(
        pattern: '/^(0[1-9]|[1-9][0-9])\d{3}$/',
        message: 'Le code postal doit être un code postal français valide'
    )]
    private ?string $postalcode = null;

    #[ORM\Column(length: 255)]
    private ?string $area_name = null;

    #[ORM\Column(length: 255)]
    private ?string $dpt_name = null;

    /**
     * @var Collection<int, Addresses>
     */
    #[ORM\OneToMany(targetEntity: Addresses::class, mappedBy: 'city')]
    private Collection $addresses;

    #[ORM\ManyToOne(inversedBy: 'cities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Countries $country = null;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name . ', ' . $this->area_name . ', ' . $this->dpt_name . ', ' . $this->country;
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

    public function getPostalcode(): ?string
    {
        return $this->postalcode;
    }

    public function setPostalcode(string $postalcode): static
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getAreaName(): ?string
    {
        return $this->area_name;
    }

    public function setAreaName(string $area_name): static
    {
        $this->area_name = $area_name;

        return $this;
    }

    public function getDptName(): ?string
    {
        return $this->dpt_name;
    }

    public function setDptName(string $dpt_name): static
    {
        $this->dpt_name = $dpt_name;

        return $this;
    }

    /**
     * @return Collection<int, Addresses>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Addresses $address): static
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setCity($this);
        }

        return $this;
    }

    public function removeAddress(Addresses $address): static
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getCity() === $this) {
                $address->setCity(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?Countries
    {
        return $this->country;
    }

    public function setCountry(?Countries $country): static
    {
        $this->country = $country;

        return $this;
    }
}
