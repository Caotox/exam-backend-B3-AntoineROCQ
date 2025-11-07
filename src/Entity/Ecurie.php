<?php

namespace App\Entity;

use App\Repository\EcurieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EcurieRepository::class)]
class Ecurie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ecurie:read', 'pilote:read', 'infraction:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Groups(['ecurie:read', 'pilote:read', 'infraction:read'])]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'ecuries')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ecurie:read'])]
    private ?Moteur $moteur = null;

    #[ORM\OneToMany(targetEntity: Pilote::class, mappedBy: 'ecurie', cascade: ['persist'])]
    #[Groups(['ecurie:read'])]
    private Collection $pilotes;

    #[ORM\OneToMany(targetEntity: Infraction::class, mappedBy: 'ecurie')]
    private Collection $infractions;

    public function __construct()
    {
        $this->pilotes = new ArrayCollection();
        $this->infractions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMoteur(): ?Moteur
    {
        return $this->moteur;
    }

    public function setMoteur(?Moteur $moteur): static
    {
        $this->moteur = $moteur;

        return $this;
    }

    public function getPilotes(): Collection
    {
        return $this->pilotes;
    }

    public function addPilote(Pilote $pilote): static
    {
        if (!$this->pilotes->contains($pilote)) {
            $this->pilotes->add($pilote);
            $pilote->setEcurie($this);
        }

        return $this;
    }

    public function removePilote(Pilote $pilote): static
    {
        if ($this->pilotes->removeElement($pilote)) {
            if ($pilote->getEcurie() === $this) {
                $pilote->setEcurie(null);
            }
        }

        return $this;
    }

    public function getInfractions(): Collection
    {
        return $this->infractions;
    }

    public function addInfraction(Infraction $infraction): static
    {
        if (!$this->infractions->contains($infraction)) {
            $this->infractions->add($infraction);
            $infraction->setEcurie($this);
        }

        return $this;
    }

    public function removeInfraction(Infraction $infraction): static
    {
        if ($this->infractions->removeElement($infraction)) {
            if ($infraction->getEcurie() === $this) {
                $infraction->setEcurie(null);
            }
        }

        return $this;
    }
}
