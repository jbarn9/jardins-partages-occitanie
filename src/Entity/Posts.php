<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
#[UniqueEntity(fields: ['slug'], message: 'Ce slug existe déjà')]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre ne peut pas être vide')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le titre doit contenir au moins 3 caractères', maxMessage: 'Le titre ne peut pas contenir plus de 255 caractères')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le contenu ne peut pas être vide')]
    private ?string $content = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Assert\Length(min: 3, max: 100, minMessage: 'Le slug doit contenir au moins 3 caractères', maxMessage: 'Le slug ne peut pas contenir plus de 100 caractères')]
    private ?string $slug = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $posted_at = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $modified_at = null;

    #[ORM\Column(nullable: true)]
    private ?int $likes_counter = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column(nullable: true)]
    private ?int $comment_counter = null;

    #[ORM\ManyToOne(inversedBy: 'tabs_posts')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Tabs $tab = null;

    /**
     * @var Collection<int, Keywords>
     */
    #[ORM\ManyToMany(targetEntity: Keywords::class, inversedBy: 'keywords_posts')]
    private Collection $keywords;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    /**
     * @var Collection<int, Categories>
     */
    #[ORM\ManyToMany(targetEntity: Categories::class, mappedBy: 'post_cat')]
    private Collection $categories;

    /**
     * @var Collection<int, Files>
     */
    #[ORM\ManyToMany(targetEntity: Files::class, inversedBy: 'posts')]
    private Collection $files;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $path = null;

    /**
     * @var Collection<int, Postmeta>
     */
    #[ORM\ManyToMany(targetEntity: Postmeta::class, mappedBy: 'posts')]
    private Collection $postmetas;

    public function __construct()
    {
        $this->keywords = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->postmetas = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->slug;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    public function getPostedAt(): ?\DateTimeImmutable
    {
        return $this->posted_at;
    }

    public function setPostedAt(\DateTimeImmutable $posted_at): static
    {
        $this->posted_at = $posted_at;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modified_at;
    }

    public function setModifiedAt(?\DateTimeImmutable $modified_at): static
    {
        $this->modified_at = $modified_at;

        return $this;
    }

    public function getLikesCounter(): ?int
    {
        return $this->likes_counter;
    }

    public function setLikesCounter(?int $likes_counter): static
    {
        $this->likes_counter = $likes_counter;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCommentCounter(): ?int
    {
        return $this->comment_counter;
    }

    public function setCommentCounter(?int $comment_counter): static
    {
        $this->comment_counter = $comment_counter;

        return $this;
    }

    public function getTab(): ?Tabs
    {
        return $this->tab;
    }

    public function setTab(?Tabs $tab): static
    {
        $this->tab = $tab;

        return $this;
    }

    /**
     * @return Collection<int, Keywords>
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(Keywords $keyword): static
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords->add($keyword);
        }

        return $this;
    }

    public function removeKeyword(Keywords $keyword): static
    {
        $this->keywords->removeElement($keyword);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addPostCat($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removePostCat($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Files>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(Files $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
        }

        return $this;
    }

    public function removeFile(Files $file): static
    {
        $this->files->removeElement($file);

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return Collection<int, Postmeta>
     */
    public function getPostmetas(): Collection
    {
        return $this->postmetas;
    }

    public function addPostmeta(Postmeta $postmeta): static
    {
        if (!$this->postmetas->contains($postmeta)) {
            $this->postmetas->add($postmeta);
            $postmeta->addPost($this);
        }

        return $this;
    }

    public function removePostmeta(Postmeta $postmeta): static
    {
        if ($this->postmetas->removeElement($postmeta)) {
            $postmeta->removePost($this);
        }

        return $this;
    }
}
