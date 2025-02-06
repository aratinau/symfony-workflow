<?php

namespace App\Entity;

use App\Repository\OrderWorkflowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderWorkflowRepository::class)]
class OrderWorkflow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, OrderWorkflowPlace>
     */
    #[ORM\OneToMany(targetEntity: OrderWorkflowPlace::class, mappedBy: 'workflow')]
    private Collection $orderWorkflowPlaces;

    public function __construct()
    {
        $this->orderWorkflowPlaces = new ArrayCollection();
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
     * @return Collection<int, OrderWorkflowPlace>
     */
    public function getOrderWorkflowPlaces(): Collection
    {
        return $this->orderWorkflowPlaces;
    }

    public function addOrderWorkflowPlace(OrderWorkflowPlace $orderWorkflowPlace): static
    {
        if (!$this->orderWorkflowPlaces->contains($orderWorkflowPlace)) {
            $this->orderWorkflowPlaces->add($orderWorkflowPlace);
            $orderWorkflowPlace->setWorkflow($this);
        }

        return $this;
    }

    public function removeOrderWorkflowPlace(OrderWorkflowPlace $orderWorkflowPlace): static
    {
        if ($this->orderWorkflowPlaces->removeElement($orderWorkflowPlace)) {
            // set the owning side to null (unless already changed)
            if ($orderWorkflowPlace->getWorkflow() === $this) {
                $orderWorkflowPlace->setWorkflow(null);
            }
        }

        return $this;
    }

    // Note: utiliser pour le rendu en front

    /**
     * Retourne un tableau associatif des états du workflow.
     *
     * @return array<string, string>
     */
    public function getStates(): array
    {
        $states = [];

        foreach ($this->orderWorkflowPlaces as $place) {
            // TODO $states[$place->getName()] = $place->getDescription();
            $states[$place->getName()] = $place->getName();
        }

        return $states;
    }

    /**
     * Retourne un tableau des transitions du workflow.
     *
     * @return array<array<string>>
     */
    public function getTransitions(): array
    {
        $transitions = [];

        foreach ($this->orderWorkflowPlaces as $place) {
            foreach ($place->getAllowedTransitions() as $nextPlace) {
                $transitions[] = [
                    $place->getName(),       // État de départ
                    $nextPlace->getName(),   // État d'arrivée
                    // TODO $place->getTransitionName(), // Nom de la transition
                    'transition name'
                ];
            }
        }

        return $transitions;
    }
}
