<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Représente un utilisateur de l'application.
 *
 * Cette entité implémente les interfaces `UserInterface` et 
 * `PasswordAuthenticatedUserInterface` pour permettre l'authentification
 * et la gestion des rôles via Symfony Security.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Identifiant unique de l'utilisateur.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nom d'utilisateur unique utilisé pour l'identification.
     *
     * @var string|null
     */
    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * Liste des rôles attribués à l'utilisateur.
     *
     * Chaque utilisateur possède au moins le rôle `ROLE_USER`.
     *
     * @var list<string>
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * Mot de passe haché de l'utilisateur.
     *
     * @var string|null
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * Retourne l'identifiant unique de l'utilisateur.
     *
     * @return int|null L'identifiant de l'utilisateur.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom d'utilisateur.
     *
     * @return string|null Le nom d'utilisateur.
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Définit le nom d'utilisateur.
     *
     * @param string $username Le nom d'utilisateur.
     * @return static
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Retourne un identifiant visuel représentant cet utilisateur.
     *
     * Cette méthode est utilisée par Symfony pour identifier les utilisateurs.
     *
     * @see UserInterface
     * @return string L'identifiant visuel.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * Retourne la liste des rôles attribués à l'utilisateur.
     *
     * Si aucun rôle n'est spécifiquement défini, l'utilisateur aura toujours
     * le rôle par défaut `ROLE_USER`.
     *
     * @see UserInterface
     * @return list<string> La liste des rôles de l'utilisateur.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER'; // Ajoute le rôle par défaut
        return array_unique($roles); // Évite les doublons
    }

    /**
     * Définit les rôles de l'utilisateur.
     *
     * @param list<string> $roles La liste des rôles à attribuer.
     * @return static
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Retourne le mot de passe haché de l'utilisateur.
     *
     * @see PasswordAuthenticatedUserInterface
     * @return string Le mot de passe haché.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Définit le mot de passe haché de l'utilisateur.
     *
     * @param string $password Le mot de passe haché.
     * @return static
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Supprime les données sensibles de l'utilisateur.
     *
     * Cette méthode peut être utilisée pour effacer des informations sensibles,
     * comme un mot de passe en clair stocké temporairement.
     *
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // Si des données sensibles étaient stockées temporairement, elles seraient supprimées ici.
        // Par exemple : $this->plainPassword = null;
    }
}
