<?php

namespace App\Entity;

use App\Repository\MoteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MoteurRepository::class)]
class Moteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ecurie:read', 'infraction:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['ecurie:read', 'infraction:read'])]
    private ?string $marque = null;

    #[ORM\OneToMany(targetEntity: Ecurie::class, mappedBy: 'moteur')]
    private Collection $ecuries;

    public function __construct()
    {
        $this->ecuries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getEcuries(): Collection
    {
        return $this->ecuries;
    }

    public function addEcurie(Ecurie $ecurie): static
    {
        if (!$this->ecuries->contains($ecurie)) {
            $this->ecuries->add($ecurie);
            $ecurie->setMoteur($this);
        }

        return $this;
    }

    public function removeEcurie(Ecurie $ecurie): static
    {
        if ($this->ecuries->removeElement($ecurie)) {
            if ($ecurie->getMoteur() === $this) {
                $ecurie->setMoteur(null);
            }
        }

        return $this;
    }
}
