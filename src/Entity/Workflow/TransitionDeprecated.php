<?php

namespace App\Entity\Workflow;

use App\Repository\Workflow\TransitionRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: TransitionRepository::class)]
class TransitionDeprecated
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: PlaceDeprecated::class)]
    private ?PlaceDeprecated $fromPlace = null;

    #[ORM\ManyToOne(targetEntity: PlaceDeprecated::class)]
    private ?PlaceDeprecated $toPlace = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $target = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromPlace(): ?PlaceDeprecated
    {
        return $this->fromPlace;
    }

    public function setFromPlace(?PlaceDeprecated $fromPlace): self
    {
        $this->fromPlace = $fromPlace;
        return $this;
    }

    public function getToPlace(): ?PlaceDeprecated
    {
        return $this->toPlace;
    }

    public function setToPlace(?PlaceDeprecated $toPlace): self
    {
        $this->toPlace = $toPlace;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(string $target): self
    {
        $this->target = $target;
        return $this;
    }
}
