<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
#[ORM\Table(name: 'FILM')]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_film', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: 'smallint')]
    private ?int $annee = null;

    #[ORM\Column(type: 'smallint')]
    private ?int $duree = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $synopsis = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $genre = null;

    #[ORM\Column(name: 'prix_location_par_defaut', type: 'decimal', precision: 5, scale: 2)]
    private ?string $prixLocationParDefaut = null;

    #[ORM\Column(name: 'chemin_affiche', length: 500, nullable: true)]
    private ?string $cheminAffiche = null;

    #[ORM\OneToMany(mappedBy: 'film', targetEntity: Favori::class)]
    private Collection $favoris;

    #[ORM\OneToMany(mappedBy: 'film', targetEntity: Location::class)]
    private Collection $locations;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
        $this->locations = new ArrayCollection();
    }

        // Ajoutez ces méthodes à la classe Film existante
// Ajoutez ces méthodes à la classe Film existante

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;
        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;
        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;
        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;
        return $this;
    }

    public function getPrixLocationParDefaut(): ?string
    {
        return $this->prixLocationParDefaut;
    }

    public function setPrixLocationParDefaut(string $prixLocationParDefaut): self
    {
        $this->prixLocationParDefaut = $prixLocationParDefaut;
        return $this;
    }

    public function getCheminAffiche(): ?string
    {
        return $this->cheminAffiche;
    }

    public function setCheminAffiche(?string $cheminAffiche): self
    {
        $this->cheminAffiche = $cheminAffiche;
        return $this;
    }

    /**
     * @return Collection<int, Favori>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favori $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
            $favori->setFilm($this);
        }
        return $this;
    }

    public function removeFavori(Favori $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getFilm() === $this) {
                $favori->setFilm(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setFilm($this);
        }
        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getFilm() === $this) {
                $location->setFilm(null);
            }
        }
        return $this;
    }

}