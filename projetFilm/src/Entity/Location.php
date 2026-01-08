<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ORM\Table(name: 'LOCATION')]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_location', type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_utilisateur', nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(targetEntity: Film::class)]
    #[ORM\JoinColumn(name: 'id_film', referencedColumnName: 'id_film', nullable: false)]
    private ?Film $film = null;

    #[ORM\Column(name: 'date_location', type: 'datetime')]
    private ?\DateTimeInterface $dateLocation = null;

    #[ORM\Column(name: 'date_retour_prevue', type: 'date')]
    private ?\DateTimeInterface $dateRetourPrevue = null;

    #[ORM\Column(name: 'prix_final', type: 'decimal', precision: 5, scale: 2)]
    private ?string $prixFinal = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = 'loué';

    public function __construct()
    {
        $this->dateLocation = new \DateTime();
        // Par défaut, retour dans 3 jours
        $this->dateRetourPrevue = (new \DateTime())->modify('+3 days');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getFilm(): ?Film
    {
        return $this->film;
    }

    public function setFilm(?Film $film): self
    {
        $this->film = $film;
        return $this;
    }

    public function getDateLocation(): ?\DateTimeInterface
    {
        return $this->dateLocation;
    }

    public function setDateLocation(\DateTimeInterface $dateLocation): self
    {
        $this->dateLocation = $dateLocation;
        return $this;
    }

    public function getDateRetourPrevue(): ?\DateTimeInterface
    {
        return $this->dateRetourPrevue;
    }

    public function setDateRetourPrevue(\DateTimeInterface $dateRetourPrevue): self
    {
        $this->dateRetourPrevue = $dateRetourPrevue;
        return $this;
    }

    public function getPrixFinal(): ?string
    {
        return $this->prixFinal;
    }

    public function setPrixFinal(string $prixFinal): self
    {
        $this->prixFinal = $prixFinal;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }
}