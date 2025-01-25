<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec cette adresse email =/')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email(message: 'Veuillez entrer une adresse email valide')]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = ['ROLE_ADMIN', 'ROLE_EDITOR'];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Regex([
        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        'message' => 'Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial',
        'groups' => ['registration'],
    ])]
    private ?string $password = null;

    #[ORM\Column(length: 60)]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $mobile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_connection = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $tokenExpirateAt = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Association $user_asso = null;

    /**
     * @var Collection<int, Posts>
     */
    #[ORM\OneToMany(targetEntity: Posts::class, mappedBy: 'user', cascade: ['remove'])]
    private Collection $posts;

    /**
     * @var Collection<int, Videos>
     */
    #[ORM\OneToMany(targetEntity: Videos::class, mappedBy: 'user')]
    private Collection $videos;

    /**
     * @var Collection<int, Resources>
     */
    #[ORM\OneToMany(targetEntity: Resources::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $resource;


    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->videos = new ArrayCollection();
        $this->resource = new ArrayCollection();
    }

    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return sprintf(

            $this->firstname . ' ' . $this->lastname

        );
    }

    // GETTERS AND SETTERS
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        if ($password) { // Vérifiez si le mot de passe n'est pas vide
            $this->password = $password;
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): static
    {
        $this->img = $img;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getLastConnection(): ?\DateTimeInterface
    {
        return $this->last_connection;
    }

    public function setLastConnection(?\DateTimeInterface $last_connection): static
    {
        $this->last_connection = $last_connection;

        return $this;
    }


    public function getIsVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token = null): static
    {
        $this->token = $token;

        return $this;
    }

    public function getTokenExpirateAt(): ?\DateTimeImmutable
    {
        return $this->tokenExpirateAt;
    }

    public function setTokenExpirateAt(\DateTimeImmutable $tokenExpirateAt = null): static
    {
        $this->tokenExpirateAt = $tokenExpirateAt;

        return $this;
    }

    public function getUserAsso(): ?Association
    {
        return $this->user_asso;
    }

    public function setUserAsso(?Association $user_asso): static
    {
        $this->user_asso = $user_asso;

        return $this;
    }

    /**
     * @return Collection<int, Posts>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Posts $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Posts $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Videos>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Videos $video): static
    {
        if (!$this->videos->contains($video)) {
            $this->videos->add($video);
            $video->setUser($this);
        }

        return $this;
    }

    public function removeVideo(Videos $video): static
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getUser() === $this) {
                $video->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Resources>
     */
    public function getResource(): Collection
    {
        return $this->resource;
    }

    public function addResource(Resources $resource): static
    {
        if (!$this->resource->contains($resource)) {
            $this->resource->add($resource);
            $resource->setUser($this);
        }

        return $this;
    }

    public function removeResource(Resources $resource): static
    {
        if ($this->resource->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getUser() === $this) {
                $resource->setUser(null);
            }
        }

        return $this;
    }
}
