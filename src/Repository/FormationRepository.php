<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Formation.
 *
 * Cette classe contient des méthodes spécifiques pour manipuler
 * les données liées aux formations, comme leur ajout, suppression,
 * et récupération selon différents critères.
 *
 * @extends ServiceEntityRepository<Formation>
 */
class FormationRepository extends ServiceEntityRepository
{
    /**
     * Initialise le repository avec le registre de gestion des entités.
     *
     * @param ManagerRegistry $registry Le registre des entités Doctrine.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    /**
     * Ajoute une formation dans la base de données.
     *
     * Cette méthode persiste l'entité et effectue une sauvegarde immédiate.
     *
     * @param Formation $entity La formation à ajouter.
     * @return void
     */
    public function add(Formation $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une formation de la base de données.
     *
     * Cette méthode supprime l'entité et effectue une sauvegarde immédiate.
     *
     * @param Formation $entity La formation à supprimer.
     * @return void
     */
    public function remove(Formation $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Retourne toutes les formations triées par un champ et un ordre spécifiés.
     *
     * @param string $champ Le champ sur lequel trier.
     * @param string $ordre L'ordre de tri (ASC ou DESC).
     * @param string $table (Optionnel) La table associée si le champ est dans une autre entité.
     * @return Formation[] La liste des formations triées.
     */
    public function findAllOrderBy(string $champ, string $ordre, string $table = ""): array
    {
        if ($table === "") {
            return $this->createQueryBuilder('f')
                ->orderBy('f.' . $champ, $ordre)
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('f')
                ->join('f.' . $table, 't')
                ->orderBy('t.' . $champ, $ordre)
                ->getQuery()
                ->getResult();
        }
    }

    /**
     * Retourne les formations où un champ contient une valeur donnée.
     *
     * Si la valeur est vide, retourne toutes les formations.
     *
     * @param string $champ Le champ à rechercher.
     * @param string $valeur La valeur à chercher.
     * @param string $table (Optionnel) La table associée si le champ est dans une autre entité.
     * @return Formation[] La liste des formations correspondantes.
     */
    public function findByContainValue(string $champ, string $valeur, string $table = ""): array
    {
        if ($valeur === "") {
            return $this->findAll();
        }
        if ($table === "") {
            return $this->createQueryBuilder('f')
                ->where('f.' . $champ . ' LIKE :valeur')
                ->orderBy('f.publishedAt', 'DESC')
                ->setParameter('valeur', '%' . $valeur . '%')
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('f')
                ->join('f.' . $table, 't')
                ->where('t.' . $champ . ' LIKE :valeur')
                ->orderBy('f.publishedAt', 'DESC')
                ->setParameter('valeur', '%' . $valeur . '%')
                ->getQuery()
                ->getResult();
        }
    }

    /**
     * Retourne les n formations les plus récentes.
     *
     * @param int $nb Le nombre de formations à retourner.
     * @return Formation[] La liste des formations les plus récentes.
     */
    public function findAllLasted(int $nb): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.publishedAt', 'DESC')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne toutes les formations associées à une playlist donnée.
     *
     * @param int $idPlaylist L'identifiant de la playlist.
     * @return Formation[] La liste des formations de la playlist.
     */
    public function findAllForOnePlaylist(int $idPlaylist): array
    {
        return $this->createQueryBuilder('f')
            ->join('f.playlist', 'p')
            ->where('p.id = :id')
            ->setParameter('id', $idPlaylist)
            ->orderBy('f.publishedAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
