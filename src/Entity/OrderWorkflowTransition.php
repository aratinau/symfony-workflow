<?php

namespace App\Entity;

use App\Repository\OrderWorkflowTransitionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderWorkflowTransitionRepository::class)]
class OrderWorkflowTransition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: OrderWorkflowPlace::class, inversedBy: 'outgoingTransitions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderWorkflowPlace $fromPlace = null;

    #[ORM\ManyToOne(targetEntity: OrderWorkflowPlace::class, inversedBy: 'incomingTransitions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderWorkflowPlace $toPlace = null;

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
        $this->name = $name;

        return $this;
    }

    public function getFromPlace(): ?OrderWorkflowPlace
    {
        return $this->fromPlace;
    }

    public function setFromPlace(?OrderWorkflowPlace $fromPlace): static
    {
        $this->fromPlace = $fromPlace;

        return $this;
    }

    public function getToPlace(): ?OrderWorkflowPlace
    {
        return $this->toPlace;
    }

    public function setToPlace(?OrderWorkflowPlace $toPlace): static
    {
        $this->toPlace = $toPlace;

        return $this;
    }
}
