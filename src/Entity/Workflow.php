<?php

namespace App\Entity;

use App\Repository\WorkflowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkflowRepository::class)]
class Workflow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $name;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description;

    #[ORM\OneToMany(targetEntity: WorkflowPlace::class, mappedBy: "workflow", cascade: ["persist", "remove"])]
    private Collection $states; // TODO rename en places

    #[ORM\OneToMany(targetEntity: WorkflowTransition::class, mappedBy: "workflow", cascade: ["persist", "remove"])]
    private Collection $transitions;

    public function __construct(string $name, ?string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->states = new ArrayCollection();
        $this->transitions = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Collection|WorkflowPlace[]
     */
    public function getStates(): Collection
    {
        return $this->states;
    }

    public function addState(WorkflowPlace $state): void
    {
        if (!$this->states->contains($state)) {
            $this->states->add($state);
            $state->setWorkflow($this);
        }
    }

    public function removeState(WorkflowPlace $state): void
    {
        if ($this->states->contains($state)) {
            $this->states->removeElement($state);
            if ($state->getWorkflow() === $this) {
                $state->setWorkflow(null);
            }
        }
    }

    /**
     * @return Collection|WorkflowTransition[]
     */
    public function getTransitions(): Collection
    {
        return $this->transitions;
    }

    public function addTransition(WorkflowTransition $transition): void
    {
        if (!$this->transitions->contains($transition)) {
            $this->transitions->add($transition);
            $transition->setWorkflow($this);
        }
    }

    public function removeTransition(WorkflowTransition $transition): void
    {
        if ($this->transitions->contains($transition)) {
            $this->transitions->removeElement($transition);
            if ($transition->getWorkflow() === $this) {
                $transition->setWorkflow(null);
            }
        }
    }
}
