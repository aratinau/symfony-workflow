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
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class)]
    private Collection $allowedTransitions;

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
        $this->allowedTransitions = new ArrayCollection();
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
     * @return Collection<int, self>
     */
    public function getAllowedTransitions(): Collection
    {
        return $this->allowedTransitions;
    }

    public function addAllowedTransition(self $allowedTransition): static
    {
        if (!$this->allowedTransitions->contains($allowedTransition)) {
            $this->allowedTransitions->add($allowedTransition);
        }

        return $this;
    }

    public function removeAllowedTransition(self $allowedTransition): static
    {
        $this->allowedTransitions->removeElement($allowedTransition);

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
