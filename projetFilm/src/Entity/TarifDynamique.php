<?php

namespace App\Entity;

use App\Repository\TarifDynamiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TarifDynamiqueRepository::class)]
#[ORM\Table(name: 'TARIF_DYNAMIQUE')]
class TarifDynamique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_tarif', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'jour_semaine', length: 20)]
    private ?string $jourSemaine = null;

    #[ORM\Column(name: 'pourcentage_reduction', type: 'decimal', precision: 5, scale: 2)]
    private ?string $pourcentageReduction = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $actif = null;

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJourSemaine(): ?string
    {
        return $this->jourSemaine;
    }

    public function setJourSemaine(string $jourSemaine): self
    {
        $this->jourSemaine = $jourSemaine;
        return $this;
    }

    public function getPourcentageReduction(): ?string
    {
        return $this->pourcentageReduction;
    }

    public function setPourcentageReduction(string $pourcentageReduction): self
    {
        $this->pourcentageReduction = $pourcentageReduction;
        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;
        return $this;
    }
}