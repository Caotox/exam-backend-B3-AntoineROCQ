<?php

namespace App\Entity;

use App\Repository\PiloteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PiloteRepository::class)]
class Pilote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pilote:read', 'ecurie:read', 'infraction:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['pilote:read', 'ecurie:read', 'infraction:read'])]
    #[Assert\NotBlank]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
    #[Groups(['pilote:read', 'ecurie:read', 'infraction:read'])]
    #[Assert\NotBlank]
    private ?string $nom = null;

    #[ORM\Column]
    #[Groups(['pilote:read', 'ecurie:read'])]
    #[Assert\Range(min: 0, max: 12)]
    private ?int $pointsLicence = 12;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['pilote:read', 'ecurie:read'])]
    #[Assert\NotNull]
    private ?\DateTimeInterface $dateDebutF1 = null;

    #[ORM\Column(length: 20)]
    #[Groups(['pilote:read', 'ecurie:read'])]
    #[Assert\Choice(choices: ['titulaire', 'reserviste', 'suspendu'])]
    private ?string $statut = 'titulaire';

    #[ORM\Column(length: 20)]
    #[Groups(['pilote:read', 'ecurie:read'])]
    private ?string $etat = 'actif';

    #[ORM\ManyToOne(inversedBy: 'pilotes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['pilote:read'])]
    private ?Ecurie $ecurie = null;

    #[ORM\OneToMany(targetEntity: Infraction::class, mappedBy: 'pilote')]
    private Collection $infractions;

    public function __construct()
    {
        $this->infractions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getPointsLicence(): ?int
    {
        return $this->pointsLicence;
    }

    public function setPointsLicence(int $pointsLicence): static
    {
        $this->pointsLicence = $pointsLicence;

        return $this;
    }

    public function getDateDebutF1(): ?\DateTimeInterface
    {
        return $this->dateDebutF1;
    }

    public function setDateDebutF1(\DateTimeInterface $dateDebutF1): static
    {
        $this->dateDebutF1 = $dateDebutF1;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

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

    public function getInfractions(): Collection
    {
        return $this->infractions;
    }

    public function addInfraction(Infraction $infraction): static
    {
        if (!$this->infractions->contains($infraction)) {
            $this->infractions->add($infraction);
            $infraction->setPilote($this);
        }

        return $this;
    }

    public function removeInfraction(Infraction $infraction): static
    {
        if ($this->infractions->removeElement($infraction)) {
            if ($infraction->getPilote() === $this) {
                $infraction->setPilote(null);
            }
        }

        return $this;
    }
}
