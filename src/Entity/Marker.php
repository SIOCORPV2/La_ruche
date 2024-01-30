<?php

namespace App\Entity;

use App\Repository\MarkerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="marker")
 */
#[ORM\Entity(repositoryClass: MarkerRepository::class)]
class Marker
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $region_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $x_coord = null;

    #[ORM\Column(nullable: true)]
    private ?int $y_coord = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(nullable: false)]
    private array $idEvent = [""];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegionId(): ?int
    {
        return $this->region_id;
    }

    public function setRegionId(?int $region_id): static
    {
        $this->region_id = $region_id;

        return $this;
    }

    public function getXCoord(): ?int
    {
        return $this->x_coord;
    }

    public function setXCoord(?int $x_coord): static
    {
        $this->x_coord = $x_coord;

        return $this;
    }

    public function getYCoord(): ?int
    {
        return $this->y_coord;
    }

    public function setYCoord(?int $y_coord): static
    {
        $this->y_coord = $y_coord;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getIdEvent(): ?array
    {
        return $this->idEvent;
    }


    public function setIdEvent(array $idEvent): static
    {
        $this->idEvent = $idEvent;

        return $this;
    }
}
