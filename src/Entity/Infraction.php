<?php

namespace App\Entity;

use App\Repository\InfractionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InfractionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Infraction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['infraction:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['infraction:read'])]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['infraction:read'])]
    #[Assert\PositiveOrZero]
    private ?int $penalitePoints = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Groups(['infraction:read'])]
    #[Assert\PositiveOrZero]
    private ?string $amendeEuros = null;

    #[ORM\Column(length: 255)]
    #[Groups(['infraction:read'])]
    #[Assert\NotBlank]
    private ?string $nomCourse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['infraction:read'])]
    private ?\DateTimeInterface $dateInfraction = null;

    #[ORM\ManyToOne(inversedBy: 'infractions')]
    #[Groups(['infraction:read'])]
    private ?Pilote $pilote = null;

    #[ORM\ManyToOne(inversedBy: 'infractions')]
    #[Groups(['infraction:read'])]
    private ?Ecurie $ecurie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPenalitePoints(): ?int
    {
        return $this->penalitePoints;
    }

    public function setPenalitePoints(?int $penalitePoints): static
    {
        $this->penalitePoints = $penalitePoints;

        return $this;
    }

    public function getAmendeEuros(): ?string
    {
        return $this->amendeEuros;
    }

    public function setAmendeEuros(?string $amendeEuros): static
    {
        $this->amendeEuros = $amendeEuros;

        return $this;
    }

    public function getNomCourse(): ?string
    {
        return $this->nomCourse;
    }

    public function setNomCourse(string $nomCourse): static
    {
        $this->nomCourse = $nomCourse;

        return $this;
    }

    public function getDateInfraction(): ?\DateTimeInterface
    {
        return $this->dateInfraction;
    }

    public function setDateInfraction(\DateTimeInterface $dateInfraction): static
    {
        $this->dateInfraction = $dateInfraction;

        return $this;
    }

    public function getPilote(): ?Pilote
    {
        return $this->pilote;
    }

    public function setPilote(?Pilote $pilote): static
    {
        $this->pilote = $pilote;

        return $this;
    }

    public function getEcurie(): ?Ecurie
    {
        return $this->ecurie;
    }

    public function setEcurie(?Ecurie $ecurie): static
    {
        $this->ecurie = $ecurie;

        return $this;
    }

    #[ORM\PrePersist]
    public function setDateInfractionValue(): void
    {
        if ($this->dateInfraction === null) {
            $this->dateInfraction = new \DateTime();
        }
    }
}
