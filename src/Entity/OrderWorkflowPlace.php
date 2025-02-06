<?php

namespace App\Entity;

use App\Repository\OrderWorkflowPlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderWorkflowPlaceRepository::class)]
class OrderWorkflowPlace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, OrderWorkflowTransition>
     */
    #[ORM\OneToMany(targetEntity: OrderWorkflowTransition::class, mappedBy: 'fromPlace', orphanRemoval: true)]
    private Collection $outgoingTransitions;

    /**
     * @var Collection<int, OrderWorkflowTransition>
     */
    #[ORM\OneToMany(targetEntity: OrderWorkflowTransition::class, mappedBy: 'toPlace', orphanRemoval: true)]
    private Collection $incomingTransitions;

    // TODO : plusieurs conditions ? singulier ? pluriels ?
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $conditions = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $allowedRoles = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $actions = [];

    #[ORM\ManyToOne(inversedBy: 'orderWorkflowPlaces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderWorkflow $workflow = null;

    public function __construct()
    {
        $this->outgoingTransitions = new ArrayCollection();
        $this->incomingTransitions = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, OrderWorkflowTransition>
     */
    public function getOutgoingTransitions(): Collection
    {
        return $this->outgoingTransitions;
    }

    public function addOutgoingTransition(OrderWorkflowTransition $outgoingTransition): static
    {
        if (!$this->outgoingTransitions->contains($outgoingTransition)) {
            $this->outgoingTransitions->add($outgoingTransition);
            $outgoingTransition->setFromPlace($this);
        }

        return $this;
    }

    public function removeOutgoingTransition(OrderWorkflowTransition $outgoingTransition): static
    {
        if ($this->outgoingTransitions->removeElement($outgoingTransition)) {
            // set the owning side to null (unless already changed)
            if ($outgoingTransition->getFromPlace() === $this) {
                $outgoingTransition->setFromPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderWorkflowTransition>
     */
    public function getIncomingTransitions(): Collection
    {
        return $this->incomingTransitions;
    }

    public function addIncomingTransition(OrderWorkflowTransition $incomingTransition): static
    {
        if (!$this->incomingTransitions->contains($incomingTransition)) {
            $this->incomingTransitions->add($incomingTransition);
            $incomingTransition->setToPlace($this);
        }

        return $this;
    }

    public function removeIncomingTransition(OrderWorkflowTransition $incomingTransition): static
    {
        if ($this->incomingTransitions->removeElement($incomingTransition)) {
            // set the owning side to null (unless already changed)
            if ($incomingTransition->getToPlace() === $this) {
                $incomingTransition->setToPlace(null);
            }
        }

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(?string $conditions): static
    {
        $this->conditions = $conditions;
        return $this;
    }

    public function getAllowedRoles(): ?array
    {
        return $this->allowedRoles;
    }

    public function setAllowedRoles(?array $allowedRoles): static
    {
        $this->allowedRoles = $allowedRoles;
        return $this;
    }
    public function getActions(): ?array
    {
        return $this->actions;
    }

    public function getWorkflow(): ?OrderWorkflow
    {
        return $this->workflow;
    }

    public function setWorkflow(?OrderWorkflow $workflow): static
    {
        $this->workflow = $workflow;

        return $this;
    }
}
