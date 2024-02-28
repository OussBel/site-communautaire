<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Trick
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Le nom ne doit pas etre vide')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La déscription ne doit pas être vide')]
    #[Assert\NotNull(message: 'La déscription ne doit pas être vide')]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modifiedAt = null;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Groupe $groupe = null;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Illustrations::class, cascade: ['persist'], orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $Illustrations;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Videos::class, cascade: ['persist'], orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $Videos;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->comments = new ArrayCollection();
        $this->Illustrations = new ArrayCollection();
        $this->Videos = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTimeInterface $modifiedAt): static
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    #[ORM\PreUpdate]
    public function setModifiedAtValue(): void
    {
        $this->modifiedAt = new \DateTime('now');
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }


    public function setGroupe(?Groupe $groupe): static
    {
        $this->groupe = $groupe;

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
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Illustrations>
     */
    public function getIllustrations(): Collection
    {
        return $this->Illustrations;
    }

    public function addIllustration(Illustrations $illustration): static
    {
        if (!$this->Illustrations->contains($illustration)) {
            $this->Illustrations->add($illustration);
            $illustration->setTrick($this);
        }

        return $this;
    }

    public function getFirstIllustration(): Illustrations
    {
        $firstIllustration = $this->Illustrations->first();

        if (!$firstIllustration) {
            $firstIllustration = new Illustrations();
            $firstIllustration->setName('empty.png');
            $firstIllustration->setTrick($this);
            $this->Illustrations->add($firstIllustration);
        }

        return $firstIllustration;
    }


    public function removeIllustration(Illustrations $illustration): static
    {
        if ($this->Illustrations->removeElement($illustration)) {
            // set the owning side to null (unless already changed)
            if ($illustration->getTrick() === $this) {
                $illustration->setTrick(null);
            }
            $this->modifiedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return Collection<int, Videos>
     */
    public function getVideos(): Collection
    {
        return $this->Videos;
    }

    public function addVideo(Videos $video): static
    {
        if (!$this->Videos->contains($video)) {
            $this->Videos->add($video);
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(Videos $video): static
    {
        if ($this->Videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

}
