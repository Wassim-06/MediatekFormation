<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Playlist.
 *
 * Cette classe contient des méthodes spécifiques pour manipuler les données
 * liées aux playlists, comme leur ajout, suppression, tri, et recherche.
 *
 * @extends ServiceEntityRepository<Playlist>
 */
class PlaylistRepository extends ServiceEntityRepository
{
    /**
     * Initialise le repository avec le registre de gestion des entités.
     *
     * @param ManagerRegistry $registry Le registre des entités Doctrine.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    /**
     * Ajoute une playlist dans la base de données.
     *
     * Cette méthode persiste l'entité et effectue une sauvegarde immédiate.
     *
     * @param Playlist $entity La playlist à ajouter.
     * @return void
     */
    public function add(Playlist $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une playlist de la base de données.
     *
     * Cette méthode supprime l'entité et effectue une sauvegarde immédiate.
     *
     * @param Playlist $entity La playlist à supprimer.
     * @return void
     */
    public function remove(Playlist $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Retourne toutes les playlists triées par nom.
     *
     * @param string $ordre L'ordre de tri (ASC ou DESC).
     * @return Playlist[] La liste des playlists triées par nom.
     */
    public function findAllOrderByName(string $ordre): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.formations', 'f') // Jointure avec les formations associées
            ->groupBy('p.id') // Regroupe par playlist
            ->orderBy('p.name', $ordre) // Tri par nom
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne toutes les playlists triées par nombre de formations.
     *
     * @param string $ordre L'ordre de tri (ASC ou DESC).
     * @return Playlist[] La liste des playlists triées par nombre de formations.
     */
    public function findAllOrderByNbrFormation(string $ordre): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.formations', 'f') // Jointure avec les formations associées
            ->groupBy('p.id') // Regroupe par playlist
            ->orderBy('p.nbrdeformation', $ordre) // Tri par nombre de formations
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les playlists où un champ contient une valeur donnée.
     *
     * Si la valeur est vide, retourne toutes les playlists triées par nom.
     *
     * @param string $champ Le champ à rechercher.
     * @param string $valeur La valeur à chercher.
     * @param string $table (Optionnel) La table associée si le champ est dans une autre entité.
     * @return Playlist[] La liste des playlists correspondantes.
     */
    public function findByContainValue(string $champ, string $valeur, string $table = ""): array
    {
        if ($valeur === "") {
            return $this->findAllOrderByName('ASC');
        }
        if ($table === "") {
            return $this->createQueryBuilder('p')
                ->leftJoin('p.formations', 'f') // Jointure avec les formations associées
                ->where('p.' . $champ . ' LIKE :valeur') // Condition sur le champ de la playlist
                ->setParameter('valeur', '%' . $valeur . '%') // Paramètre sécurisé
                ->groupBy('p.id') // Regroupe par playlist
                ->orderBy('p.name', 'ASC') // Tri par nom
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('p')
                ->leftJoin('p.formations', 'f') // Jointure avec les formations associées
                ->leftJoin('f.categories', 'c') // Jointure avec les catégories associées
                ->where('c.' . $champ . ' LIKE :valeur') // Condition sur le champ de la catégorie
                ->setParameter('valeur', '%' . $valeur . '%') // Paramètre sécurisé
                ->groupBy('p.id') // Regroupe par playlist
                ->orderBy('p.name', 'ASC') // Tri par nom
                ->getQuery()
                ->getResult();
        }
    }
}
