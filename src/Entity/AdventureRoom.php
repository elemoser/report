<?php

namespace App\Entity;

use App\Repository\AdventureRoomRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdventureRoomRepository::class)]
class AdventureRoom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $north = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $east = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $south = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $west = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $inspect = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNorth(): ?string
    {
        return $this->north;
    }

    public function setNorth(?string $north): self
    {
        $this->north = $north;

        return $this;
    }

    public function getEast(): ?string
    {
        return $this->east;
    }

    public function setEast(?string $east): self
    {
        $this->east = $east;

        return $this;
    }

    public function getSouth(): ?string
    {
        return $this->south;
    }

    public function setSouth(?string $south): self
    {
        $this->south = $south;

        return $this;
    }

    public function getWest(): ?string
    {
        return $this->west;
    }

    public function setWest(?string $west): self
    {
        $this->west = $west;

        return $this;
    }

    public function getInspect(): ?string
    {
        return $this->inspect;
    }

    public function setInspect(string $inspect): self
    {
        $this->inspect = $inspect;

        return $this;
    }
}
