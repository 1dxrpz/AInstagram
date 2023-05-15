<?php

namespace App\Entity;

use App\Repository\UserPostsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserPostsRepository::class)]
class UserPosts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $PostID = null;

    #[ORM\Column]
    private ?int $UserID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostID(): ?int
    {
        return $this->PostID;
    }

    public function setPostID(int $PostID): self
    {
        $this->PostID = $PostID;

        return $this;
    }

    public function getUserID(): ?int
    {
        return $this->UserID;
    }

    public function setUserID(int $UserID): self
    {
        $this->UserID = $UserID;

        return $this;
    }
}
