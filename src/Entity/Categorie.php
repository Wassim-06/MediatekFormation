<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Représente une catégorie de formation.
 *
 * Une catégorie regroupe plusieurs formations et peut être associée
 * à plusieurs entités de type "Formation".
 */
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    /**
     * Identifiant unique de la catégorie.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nom de la catégorie.
     *
     * @var string|null
     */
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $name = null;

    /**
     * Liste des formations associées à cette catégorie.
     *
     * @var Collection<int, Formation>
     */
    #[ORM\ManyToMany(targetEntity: Formation::class, mappedBy: 'categories')]
    private Collection $formations;

    /**
     * Initialise la collection des formations.
     */
    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant unique de la catégorie.
     *
     * @return int|null L'identifiant de la catégorie.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom de la catégorie.
     *
     * @return string|null Le nom de la catégorie.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Définit le nom de la catégorie.
     *
     * @param string|null $name Le nouveau nom de la catégorie.
     * @return static Retourne l'instance de la catégorie.
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

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
     * Ajoute une formation à la liste des formations de cette catégorie.
     *
     * Si la formation est déjà associée, elle n'est pas ajoutée à nouveau.
     *
     * @param Formation $formation La formation à ajouter.
     * @return static Retourne l'instance de la catégorie.
     */
    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->addCategory($this);
        }

        return $this;
    }

    /**
     * Supprime une formation de la liste des formations de cette catégorie.
     *
     * Si la formation est présente, elle est retirée et l'association
     * est également supprimée de l'entité Formation.
     *
     * @param Formation $formation La formation à retirer.
     * @return static Retourne l'instance de la catégorie.
     */
    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation)) {
            $formation->removeCategory($this);
        }

        return $this;
    }
}
