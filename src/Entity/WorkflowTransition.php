<?php

namespace App\Entity;

use App\Repository\WorkflowTransitionRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Workflow
 * @deprecated
 */
#[ORM\Entity(repositoryClass: WorkflowTransitionRepository::class)]
class WorkflowTransition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: WorkflowPlace::class)]
    #[ORM\JoinColumn(name: "from_state_id", referencedColumnName: "id", nullable: false)]
    private WorkflowPlace $fromState;

    #[ORM\ManyToOne(targetEntity: WorkflowPlace::class)]
    #[ORM\JoinColumn(name: "to_state_id", referencedColumnName: "id", nullable: false)]
    private WorkflowPlace $toState;

    #[ORM\Column(type: "string", length: 255)]
    private string $strategy;

    #[ORM\ManyToOne(targetEntity: Workflow::class, inversedBy: "transitions")]
    #[ORM\JoinColumn(nullable: false)]
    private Workflow $workflow;

    #TODO #[ORM\Column(type: 'string', length: 50)]
    #TODO private string $type; // Exemple : "Manuel", "Automatique"

    public function __construct(WorkflowPlace $fromState, WorkflowPlace $toState, string $strategy)
    {
        $this->fromState = $fromState;
        $this->toState = $toState;
        $this->strategy = $strategy;
    }

    // Getters et Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getFromState(): WorkflowPlace
    {
        return $this->fromState;
    }

    public function setFromState(WorkflowPlace $fromState): void
    {
        $this->fromState = $fromState;
    }

    public function getToState(): WorkflowPlace
    {
        return $this->toState;
    }

    public function setToState(WorkflowPlace $toState): void
    {
        $this->toState = $toState;
    }

    public function getStrategy(): string
    {
        return $this->strategy;
    }

    public function setStrategy(string $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function getWorkflow(): Workflow
    {
        return $this->workflow;
    }

    public function setWorkflow(Workflow $workflow): void
    {
        $this->workflow = $workflow;
    }

}
