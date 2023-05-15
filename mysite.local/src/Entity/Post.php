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
    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public ?string $Prompt = null;

    #[ORM\Column(length: 2048, nullable: true)]
    #[Assert\NotBlank]
    public ?string $Description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    public ?string $ImageURL = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    public ?string $Title = null;
}