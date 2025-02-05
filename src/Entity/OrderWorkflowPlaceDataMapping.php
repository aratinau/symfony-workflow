<?php

namespace App\Entity;

use App\Repository\OrderWorkflowPlaceDataMappingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderWorkflowPlaceDataMappingRepository::class)]
class OrderWorkflowPlaceDataMapping
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'dataMappings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderWorkflowPlace $place = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderWorkflowPlaceData $data = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlace(): ?OrderWorkflowPlace
    {
        return $this->place;
    }

    public function setPlace(?OrderWorkflowPlace $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getData(): ?OrderWorkflowPlaceData
    {
        return $this->data;
    }

    public function setData(?OrderWorkflowPlaceData $data): static
    {
        $this->data = $data;

        return $this;
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
}
