<?php

namespace App\Entity;

use App\Repository\AssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity(repositoryClass: AssociationRepository::class)]
class Association
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $founded_at = null;

    #[ORM\Column(length: 100)]
    private ?string $mantra = null;

    #[ORM\Column(length: 255)]
    private ?string $banner = null;

    #[ORM\Column]
    private ?bool $isLucrative = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'user_asso')]
    private Collection $users;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $acronyme = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 20)]
    private ?string $mobile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $update_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'asso_address')]
    private ?Addresses $address = null;

    /**
     * @var Collection<int, Links>
     */
    #[ORM\OneToMany(targetEntity: Links::class, mappedBy: 'association')]
    private Collection $links;

    /**
     * @var Collection<int, SubjectEmail>
     */
    #[ORM\OneToMany(targetEntity: SubjectEmail::class, mappedBy: 'association')]
    private Collection $subjectEmails;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->links = new ArrayCollection();
        $this->subjectEmails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFoundedAt(): ?\DateTimeImmutable
    {
        return $this->founded_at;
    }

    public function setFoundedAt(\DateTimeImmutable $founded_at): static
    {
        $this->founded_at = $founded_at;

        return $this;
    }

    public function getMantra(): ?string
    {
        return $this->mantra;
    }

    public function setMantra(string $mantra): static
    {
        $this->mantra = $mantra;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(string $banner): static
    {
        $this->banner = $banner;

        return $this;
    }

    public function isLucrative(): ?bool
    {
        return $this->isLucrative;
    }

    public function setLucrative(bool $isLucrative): static
    {
        $this->isLucrative = $isLucrative;

        return $this;
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
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setUserAsso($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getUserAsso() === $this) {
                $user->setUserAsso(null);
            }
        }

        return $this;
    }

    public function getAcronyme(): ?string
    {
        return $this->acronyme;
    }

    public function setAcronyme(?string $acronyme): static
    {
        $this->acronyme = $acronyme;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->update_at;
    }

    public function setUpdateAt(?\DateTimeImmutable $update_at): static
    {
        $this->update_at = $update_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getAddress(): ?Addresses
    {
        return $this->address;
    }

    public function setAddress(?Addresses $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Links>
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(Links $link): static
    {
        if (!$this->links->contains($link)) {
            $this->links->add($link);
            $link->setAssociation($this);
        }

        return $this;
    }

    public function removeLink(Links $link): static
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getAssociation() === $this) {
                $link->setAssociation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SubjectEmail>
     */
    public function getSubjectEmails(): Collection
    {
        return $this->subjectEmails;
    }

    public function addSubjectEmail(SubjectEmail $subjectEmail): static
    {
        if (!$this->subjectEmails->contains($subjectEmail)) {
            $this->subjectEmails->add($subjectEmail);
            $subjectEmail->setAssociation($this);
        }

        return $this;
    }

    public function removeSubjectEmail(SubjectEmail $subjectEmail): static
    {
        if ($this->subjectEmails->removeElement($subjectEmail)) {
            // set the owning side to null (unless already changed)
            if ($subjectEmail->getAssociation() === $this) {
                $subjectEmail->setAssociation(null);
            }
        }

        return $this;
    }
}
