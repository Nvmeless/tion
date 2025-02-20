<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\IdentityRepository;
use App\Traits\StatisticsPropertiesTrait;

#[ORM\Entity(repositoryClass: IdentityRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Identity
{

    use StatisticsPropertiesTrait;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;



    public function getId(): ?int
    {
        return $this->id;
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
}
