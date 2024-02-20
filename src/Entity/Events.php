<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use DateTime;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventsRepository::class)]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    private ?int $id_marker = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?DateTime $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdMarker(): ?int
    {
        return $this->id_marker;
    }

    public function setIdMarker(?int $id_marker): void
    {
        $this->id_marker = $id_marker;
    }



    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): void
    {
        $this->date = $date;
    }





}
