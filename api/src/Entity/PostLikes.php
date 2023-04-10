<?php

namespace App\Entity;

use App\Repository\PostLikesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostLikesRepository::class)]
class PostLikes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Likes = null;

    #[ORM\Column]
    private ?int $Dislikes = null;

    #[ORM\Column(length: 255)]
    private ?string $PostID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLikes(): ?int
    {
        return $this->Likes;
    }

    public function setLikes(int $Likes): self
    {
        $this->Likes = $Likes;

        return $this;
    }

    public function getDislikes(): ?int
    {
        return $this->Dislikes;
    }

    public function setDislikes(int $Dislikes): self
    {
        $this->Dislikes = $Dislikes;

        return $this;
    }

    public function getPostID(): ?string
    {
        return $this->PostID;
    }

    public function setPostID(string $PostID): self
    {
        $this->PostID = $PostID;

        return $this;
    }
}
