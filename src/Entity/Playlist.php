<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Représente une playlist contenant plusieurs formations.
 *
 * Une playlist regroupe des formations, a un nom, une description, et un
 * compteur pour le nombre total de formations qu'elle contient.
 */
#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    /**
     * Identifiant unique de la playlist.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nom de la playlist.
     *
     * @var string|null
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $name = null;

    /**
     * Description de la playlist.
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * Liste des formations associées à cette playlist.
     *
     * @var Collection<int, Formation>
     */
    #[ORM\OneToMany(targetEntity: Formation::class, mappedBy: 'playlist')]
    private Collection $formations;

    /**
     * Nombre total de formations associées à cette playlist.
     *
     * @var int|null
     */
    #[ORM\Column]
    private ?int $nbrdeformation = null;

    /**
     * Initialise la collection des formations.
     */
    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant unique de la playlist.
     *
     * @return int|null L'identifiant de la playlist.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom de la playlist.
     *
     * @return string|null Le nom de la playlist.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Définit le nom de la playlist.
     *
     * @param string|null $name Le nom à définir.
     * @return static
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Retourne la description de la playlist.
     *
     * @return string|null La description de la playlist.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description de la playlist.
     *
     * @param string|null $description La description à définir.
     * @return static
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Retourne la liste des formations associées.
     *
     * @return Collection<int, Formation> La collection des formations.
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    /**
     * Ajoute une formation à la playlist.
     *
     * Si la formation est déjà associée, elle ne sera pas ajoutée à nouveau.
     *
     * @param Formation $formation La formation à ajouter.
     * @return static
     */
    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setPlaylist($this);
        }

        return $this;
    }

    /**
     * Supprime une formation de la playlist.
     *
     * Si la formation est associée, elle sera retirée de la collection
     * et la relation sera supprimée côté formation.
     *
     * @param Formation $formation La formation à retirer.
     * @return static
     */
    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation) && $formation->getPlaylist() === $this) {
            // Supprime l'association côté Formation
            $formation->setPlaylist(null);
        }

        return $this;
    }

    /**
     * Retourne une collection de noms de catégories associées à la playlist.
     *
     * Cette méthode parcourt toutes les formations de la playlist et récupère
     * les noms des catégories associées, en supprimant les doublons.
     *
     * @return Collection<int, string> Une collection contenant les noms des catégories.
     */
    public function getCategoriesPlaylist(): Collection
    {
        $categories = new ArrayCollection();
        foreach ($this->formations as $formation) {
            $categoriesFormation = $formation->getCategories();
            foreach ($categoriesFormation as $categorieFormation) {
                if (!$categories->contains($categorieFormation->getName())) {
                    $categories[] = $categorieFormation->getName();
                }
            }
        }
        return $categories;
    }

    /**
     * Retourne le nombre total de formations associées à la playlist.
     *
     * @return int|null Le nombre de formations.
     */
    public function getNbrdeformation(): ?int
    {
        return $this->nbrdeformation;
    }

    /**
     * Définit le nombre total de formations associées à la playlist.
     *
     * @param int $nbrdeformation Le nombre à définir.
     * @return static
     */
    public function setNbrdeformation(int $nbrdeformation): static
    {
        $this->nbrdeformation = $nbrdeformation;

        return $this;
    }
}
