<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\Groups;
use App\Repository\AddressesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
    ]
)]
#[ORM\Entity(repositoryClass: AddressesRepository::class)]
class Addresses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['addresses:list', 'addresses:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['addresses:list', 'addresses:item'])]
    private ?string $street = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0, nullable: true)]
    #[Groups(['addresses:list', 'addresses:item'])]
    private ?string $longitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7, nullable: true)]
    #[Groups(['addresses:list', 'addresses:item'])]
    private ?string $latitude = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['addresses:list', 'addresses:item'])]
    private ?Cities $city = null;

    /**
     * @var Collection<int, Association>
     */
    #[ORM\OneToMany(targetEntity: Association::class, mappedBy: 'address')]
    private Collection $asso_address;

    public function __construct()
    {
        $this->asso_address = new ArrayCollection();
    }
    public function __toString()
    {
        return  $this->street . ', ' . $this->city->getPostalcode() . ' ' . $this->city->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getCity(): ?Cities
    {
        return $this->city;
    }

    public function setCity(?Cities $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, Association>
     */
    public function getAssoAddress(): Collection
    {
        return $this->asso_address;
    }

    public function addAssoAddress(Association $assoAddress): static
    {
        if (!$this->asso_address->contains($assoAddress)) {
            $this->asso_address->add($assoAddress);
            $assoAddress->setAddress($this);
        }

        return $this;
    }

    public function removeAssoAddress(Association $assoAddress): static
    {
        if ($this->asso_address->removeElement($assoAddress)) {
            // set the owning side to null (unless already changed)
            if ($assoAddress->getAddress() === $this) {
                $assoAddress->setAddress(null);
            }
        }

        return $this;
    }
}
