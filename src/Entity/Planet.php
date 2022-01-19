<?php

namespace App\Entity;

use App\Repository\PlanetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PlanetRepository::class)]
class Planet
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer', unique: true)]
    #[Assert\NotNull(payload: ['severity' => 'error'])]
    #[Assert\NotBlank(payload: ['severity' => 'error'])]
    #[Assert\Positive(payload: ['severity' => 'error'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $rotation_period;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $orbital_period;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $diameter;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $films_count;

    #[ORM\Column(type: 'datetime')]
    private $created;

    #[ORM\Column(type: 'datetime')]
    private $edited;

    private $url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getRotationPeriod(): ?int
    {
        return $this->rotation_period;
    }

    public function setRotationPeriod(?int $rotation_period): self
    {
        $this->rotation_period = $rotation_period;

        return $this;
    }

    public function getOrbitalPeriod(): ?int
    {
        return $this->orbital_period;
    }

    public function setOrbitalPeriod(?int $orbital_period): self
    {
        $this->orbital_period = $orbital_period;

        return $this;
    }

    public function getDiameter(): ?int
    {
        return $this->diameter;
    }

    public function setDiameter(?int $diameter): self
    {
        $this->diameter = $diameter;

        return $this;
    }

    public function getFilmsCount(): ?int
    {
        return $this->films_count;
    }

    public function setFilmsCount(?int $films_count): self
    {
        $this->films_count = $films_count;

        return $this;
    }

    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getEdited(): ?\DateTime
    {
        return $this->edited;
    }

    public function setEdited(\DateTime $edited): self
    {
        $this->edited = $edited;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist() {
        $this->created = new \DateTime('now');
        $this->edited = new \DateTime('now');
    }
    
    #[ORM\PreUpdate]
    public function preUpdate() {
        $this->updated = new \DateTime('now');
    }

    #[ORM\PostLoad]
    public function postLoad() {
        $protocol = 'http://';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $protocol = 'https://';
        }
        $route = $protocol . $_SERVER['HTTP_HOST'] . '/planets/' . $this->getId();
        $this->url = $route;
    }
}
