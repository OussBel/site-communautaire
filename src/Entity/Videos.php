<?php

namespace App\Entity;

use App\Repository\VideosRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VideosRepository::class)]
class Videos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez ajouter un lien video valide (lien intégré)')]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'videos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trick $trick = null;

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
        if (preg_match('#https://www\.youtube\.com/embed/([a-zA-Z0-9_\-=?]+)#', $name, $matches)) {
            $name = "https://www.youtube.com/embed/" . $matches[1];
        } elseif (preg_match('#https://www\.dailymotion\.com/embed/video/([^"]+)#', $name, $matches)) {
            $name = "https://www.dailymotion.com/embed/video/" . $matches[1];
        } else {
            $name = null;
        }

        $this->name = $name;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): static
    {
        $this->trick = $trick;

        return $this;
    }
}
