<?php

namespace App\Entity;

use App\Repository\WorkflowPlaceRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Workflow
 * @deprecated
 */
#[ORM\Entity(repositoryClass: WorkflowPlaceRepository::class)]
class WorkflowPlace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $name;

    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Workflow::class, inversedBy: "states")]
    #[ORM\JoinColumn(nullable: false)]
    private Workflow $workflow;

    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    // Getters et Setters
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
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
