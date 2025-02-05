<?php

namespace App\Entity;

use App\Repository\OrderWorklowPlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderWorklowPlaceRepository::class)]
class OrderWorklowPlace
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
}
