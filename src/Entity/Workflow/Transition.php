<?php

namespace App\Entity\Workflow;

use App\Repository\Workflow\TransitionRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: TransitionRepository::class)]
class Transition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Place::class)]
    private ?Place $fromPlace = null;

    #[ORM\ManyToOne(targetEntity: Place::class)]
    private ?Place $toPlace = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $target = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromPlace(): ?Place
    {
        return $this->fromPlace;
    }

    public function setFromPlace(?Place $fromPlace): self
    {
        $this->fromPlace = $fromPlace;
        return $this;
    }

    public function getToPlace(): ?Place
    {
        return $this->toPlace;
    }

    public function setToPlace(?Place $toPlace): self
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
