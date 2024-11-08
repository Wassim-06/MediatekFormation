<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Représente une formation associée à des playlists et des catégories.
 *
 * Une formation contient des informations comme un titre, une description, une date de publication,
 * et une vidéo associée (via un ID YouTube).
 */
#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    /**
     * Chemin de base pour accéder aux images associées à une vidéo YouTube.
     */
    private const CHEMININMAGE = "https://i.ytimg.com/vi/";

    /**
     * Identifiant unique de la formation.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Date de publication de la formation.
     *
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Range(
        min: 'now',
        max: '+5 years',
        notInRangeMessage: 'You must be between {{ min }} and {{ max }} to enter',
    )]
    private ?\DateTimeInterface $publishedAt = null;

    /**
     * Titre de la formation.
     *
     * @var string|null
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $title = null;

    /**
     * Description de la formation.
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * Identifiant de la vidéo associée (YouTube).
     *
     * @var string|null
     */
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $videoId = null;

    /**
     * Playlist associée à la formation.
     *
     * @var Playlist|null
     */
    #[ORM\ManyToOne(inversedBy: 'formations')]
    private ?Playlist $playlist = null;

    /**
     * Liste des catégories associées à cette formation.
     *
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'formations')]
    private Collection $categories;

    /**
     * Initialise la collection des catégories.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant unique de la formation.
     *
     * @return int|null L'identifiant de la formation.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne la date de publication.
     *
     * @return \DateTimeInterface|null La date de publication.
     */
    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    /**
     * Définit la date de publication.
     *
     * @param \DateTimeInterface|null $publishedAt La date à définir.
     * @return static
     */
    public function setPublishedAt(?\DateTimeInterface $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Retourne la date de publication sous forme de chaîne de caractères.
     *
     * @return string La date formatée ou une chaîne vide si aucune date n'est définie.
     */
    public function getPublishedAtString(): string
    {
        if ($this->publishedAt === null) {
            return "";
        }
        return $this->publishedAt->format('d/m/Y');
    }

    /**
     * Retourne le titre de la formation.
     *
     * @return string|null Le titre de la formation.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Définit le titre de la formation.
     *
     * @param string|null $title Le titre à définir.
     * @return static
     */
    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Retourne la description de la formation.
     *
     * @return string|null La description de la formation.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description de la formation.
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
     * Retourne l'identifiant de la vidéo associée.
     *
     * @return string|null L'identifiant de la vidéo (YouTube).
     */
    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    /**
     * Définit l'identifiant de la vidéo associée.
     *
     * @param string|null $videoId L'identifiant à définir.
     * @return static
     */
    public function setVideoId(?string $videoId): static
    {
        $this->videoId = $videoId;

        return $this;
    }

    /**
     * Retourne l'URL de la miniature de la vidéo associée.
     *
     * @return string|null L'URL de la miniature.
     */
    public function getMiniature(): ?string
    {
        return self::CHEMININMAGE . $this->videoId . "/default.jpg";
    }

    /**
     * Retourne l'URL de l'image de haute qualité de la vidéo associée.
     *
     * @return string|null L'URL de l'image.
     */
    public function getPicture(): ?string
    {
        return self::CHEMININMAGE . $this->videoId . "/hqdefault.jpg";
    }

    /**
     * Retourne la playlist associée à cette formation.
     *
     * @return Playlist|null La playlist associée.
     */
    public function getPlaylist(): ?playlist
    {
        return $this->playlist;
    }

    /**
     * Définit la playlist associée à cette formation.
     *
     * @param Playlist|null $playlist La playlist à associer.
     * @return static
     */
    public function setPlaylist(?Playlist $playlist): static
    {
        $this->playlist = $playlist;

        return $this;
    }

    /**
     * Retourne la liste des catégories associées à cette formation.
     *
     * @return Collection<int, Categorie> La collection des catégories.
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * Ajoute une catégorie à la formation.
     *
     * @param Categorie $category La catégorie à ajouter.
     * @return static
     */
    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    /**
     * Retire une catégorie de la formation.
     *
     * @param Categorie $category La catégorie à retirer.
     * @return static
     */
    public function removeCategory(Categorie $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }
}
