<?php

namespace App\Entity;

use App\Repository\ClassroomRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClassroomRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Classroom implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @Assert\NotNull
     */
    private $isActive;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'isActive' => $this->getIsActive(),
        ];
    }

    /**
     * @throws \Exception
     * @ORM\PrePersist()
     */
    public function beforeSave()
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTime('now');
        }
    }
}




