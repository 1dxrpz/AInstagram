<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 2048)]
    public ?string $Prompt = null;

    #[ORM\Column(length: 2048, nullable: true)]
    public ?string $Description = null;

    #[ORM\Column(length: 255)]
    public ?string $ImageURL = null;
    
    #[ORM\Column(length: 255)]
    public ?string $Title = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrompt(): ?string
    {
        return $this->Prompt;
    }

    public function setPrompt(string $Prompt): self
    {
        $this->Prompt = $Prompt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getImageURL(): ?string
    {
        return $this->ImageURL;
    }

    public function setImageURL(string $ImageURL): self
    {
        $this->ImageURL = $ImageURL;

        return $this;
    }
    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }
}
