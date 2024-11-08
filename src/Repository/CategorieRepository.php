<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Categorie.
 *
 * Cette classe contient des méthodes spécifiques pour manipuler
 * les données liées aux catégories, comme leur ajout, suppression,
 * et récupération en fonction des relations.
 *
 * @extends ServiceEntityRepository<Categorie>
 */
class CategorieRepository extends ServiceEntityRepository
{
    /**
     * Initialise le repository avec le registre de gestion des entités.
     *
     * @param ManagerRegistry $registry Le registre des entités Doctrine.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    /**
     * Ajoute une catégorie dans la base de données.
     *
     * Cette méthode persiste l'entité et effectue une sauvegarde immédiate.
     *
     * @param Categorie $entity La catégorie à ajouter.
     * @return void
     */
    public function add(Categorie $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une catégorie de la base de données.
     *
     * Cette méthode supprime l'entité et effectue une sauvegarde immédiate.
     *
     * @param Categorie $entity La catégorie à supprimer.
     * @return void
     */
    public function remove(Categorie $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Récupère la liste des catégories associées aux formations d'une playlist donnée.
     *
     * Cette méthode effectue une jointure entre les catégories, les formations,
     * et la playlist, pour récupérer uniquement les catégories liées à une playlist spécifique.
     *
     * @param int $idPlaylist L'identifiant de la playlist.
     * @return array La liste des catégories associées, triées par nom.
     */
    public function findAllForOnePlaylist(int $idPlaylist): array
    {
        return $this->createQueryBuilder('c') // Alias pour l'entité Categorie
            ->join('c.formations', 'f') // Jointure avec les formations associées
            ->join('f.playlist', 'p') // Jointure avec la playlist des formations
            ->where('p.id = :id') // Condition pour la playlist spécifique
            ->setParameter('id', $idPlaylist) // Paramètre pour sécuriser la requête
            ->orderBy('c.name', 'ASC') // Tri des catégories par nom (ascendant)
            ->getQuery()
            ->getResult(); // Exécution de la requête et récupération des résultats
    }
}
