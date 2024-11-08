<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * Repository pour l'entité User.
 *
 * Cette classe gère l'accès aux données des utilisateurs et contient
 * des méthodes spécifiques pour manipuler les utilisateurs, notamment
 * la mise à jour des mots de passe.
 *
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * Initialise le repository avec le registre de gestion des entités.
     *
     * @param ManagerRegistry $registry Le registre des entités Doctrine.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Met à jour (rehash) le mot de passe de l'utilisateur.
     *
     * Cette méthode est utilisée pour re-hacher le mot de passe lorsque
     * l'algorithme de hachage change ou pour renforcer la sécurité au fil du temps.
     *
     * @param PasswordAuthenticatedUserInterface $user L'utilisateur dont le mot de passe doit être mis à jour.
     * @param string $newHashedPassword Le nouveau mot de passe haché.
     * @throws UnsupportedUserException Si l'utilisateur n'est pas une instance de `User`.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances de "%s" ne sont pas prises en charge.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Méthode personnalisée pour trouver des utilisateurs par un champ donné (exemple désactivé).
     *
     * Cette méthode peut être utilisée comme base pour ajouter des requêtes personnalisées.
     *
     * @param mixed $value La valeur à chercher.
     * @return User[] Retourne un tableau d'objets User correspondant à la condition.
     */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('u.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('u.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    /**
     * Méthode personnalisée pour trouver un utilisateur unique par un champ donné (exemple désactivé).
     *
     * Cette méthode peut être utilisée pour des recherches spécifiques.
     *
     * @param mixed $value La valeur à chercher.
     * @return User|null Retourne un utilisateur ou null s'il n'est pas trouvé.
     */
    // public function findOneBySomeField($value): ?User
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('u.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}
