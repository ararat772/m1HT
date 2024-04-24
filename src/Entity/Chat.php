<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
#[ORM\Table(name: "chat")]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $status;

    #[ORM\Column(type: "datetime")]
    private \DateTime $createdAt;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $isResolved;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTime $updatedAt;

    #[ORM\OneToMany(mappedBy: "chat", targetEntity: "Message")]
    private Collection $messages;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->messages  = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setIsResolved(?bool $isResolved): self
    {
        $this->isResolved = $isResolved;
        return $this;
    }
}
