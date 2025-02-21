<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\HistoryRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HistoryRepository::class)]
class History
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'histories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HistoryContext $context = null;

    #[ORM\ManyToOne(inversedBy: 'histories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HistoryContextType $subcontext = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: "le payload doit etre null ou  etre un JSON")]
    private ?array $payload = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $at = null;


    public function __construct()
    {
        $this->setAt(new DateTime());
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContext(): ?HistoryContext
    {
        return $this->context;
    }

    public function setContext(?HistoryContext $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getSubcontext(): ?HistoryContextType
    {
        return $this->subcontext;
    }

    public function setSubcontext(?HistoryContextType $subcontext): static
    {
        $this->subcontext = $subcontext;

        return $this;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }

    public function setPayload(?array $payload): static
    {
        $this->payload = $payload;

        return $this;
    }

    public function getAt(): ?\DateTimeInterface
    {
        return $this->at;
    }

    public function setAt(\DateTimeInterface $at): static
    {
        $this->at = $at;

        return $this;
    }
}
