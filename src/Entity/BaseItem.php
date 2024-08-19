<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BaseItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BaseItemRepository::class)]
#[ApiResource]
class BaseItem
{
    const STATUSES = [
        'SENT' => 'SENT',
        'READ' => 'READ',
        'ARCHIVED' => 'ARCHIVED',
        'CANCELLED' => 'CANCELLED',
        'NOT_READ' => 'NOT_READ',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $publicStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->publicStatus = self::STATUSES['SENT'];
    }

    public function getPublicStatus(): ?string
    {
        return $this->publicStatus;
    }

    public function setPublicStatus(string $publicStatus): static
    {
        $this->publicStatus = $publicStatus;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
