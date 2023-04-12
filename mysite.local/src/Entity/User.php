<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    public $name;

    #[ORM\Column]
    #[Assert\NotBlank]
    public ?string $password = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    public ?string $confirm_password = null;

    #[ORM\Column(length: 64)]
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;
}