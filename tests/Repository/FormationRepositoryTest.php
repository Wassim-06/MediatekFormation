<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Classe de tests pour le repository FormationRepository.
 *
 * Cette classe vérifie les fonctionnalités suivantes :
 * - Compte des formations.
 * - Ajout et suppression de formations.
 * - Recherche avec un champ contenant une valeur.
 * - Tri des formations.
 * - Récupération des formations les plus récentes.
 * - Récupération des formations associées à une playlist spécifique.
 */
class FormationRepositoryTest extends KernelTestCase
{
    /**
     * Récupère une instance du repository FormationRepository.
     *
     * @return FormationRepository
     */
    public function recupRepository(): FormationRepository
    {
        self::bootKernel(); // Lance le noyau Symfony
        return self::getContainer()->get(FormationRepository::class); // Récupère le repository
    }

    /**
     * Teste le nombre total de formations dans la base de données.
     */
    public function testNbFormations(): void
    {
        $repository = $this->recupRepository();

        // Compte toutes les formations dans la base
        $nbFormations = $repository->count([]);

        // Vérifie que le nombre correspond au nombre attendu (9 ici)
        $this->assertEquals(9, $nbFormations, "Le nombre de formations ne correspond pas.");
    }

    /**
     * Crée une nouvelle instance de la classe Formation pour les tests.
     *
     * @return Formation
     */
    public function newFormation(): Formation
    {
        $formation = (new Formation())
            ->setTitle("Test")
            ->setVideoId("123")
            ->setPublishedAt(new \DateTime("2025-01-04 17:00:12")); // Date future pour les tests
        return $formation;
    }

    /**
     * Teste l'ajout d'une nouvelle formation.
     */
    public function testAddFormation(): void
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();

        // Compte les formations avant l'ajout
        $nbFormations = $repository->count([]);

        // Ajoute une nouvelle formation
        $repository->add($formation, true);

        // Vérifie que le nombre de formations a augmenté de 1
        $this->assertEquals(
            $nbFormations + 1,
            $repository->count([]),
            "Erreur lors de l'ajout d'une formation."
        );
    }

    /**
     * Teste la suppression d'une formation.
     */
    public function testRemoveFormation(): void
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();

        // Ajoute une formation pour la supprimer ensuite
        $repository->add($formation, true);

        // Compte les formations avant la suppression
        $nbFormations = $repository->count([]);

        // Supprime la formation ajoutée
        $repository->remove($formation, true);

        // Vérifie que le nombre de formations a diminué de 1
        $this->assertEquals(
            $nbFormations - 1,
            $repository->count([]),
            "Erreur lors de la suppression d'une formation."
        );
    }

    /**
     * Teste la recherche de formations contenant une valeur dans un champ donné.
     */
    public function testFindByContainValue(): void
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();

        // Ajoute une formation pour tester la recherche
        $repository->add($formation, true);

        // Recherche les formations contenant "Test" dans le champ "title"
        $formations = $repository->findByContainValue("title", "Test");

        // Vérifie que le titre de la première formation correspond à "Test"
        $this->assertEquals("Test", $formations[0]->getTitle(), "La recherche n'a pas retourné le bon résultat.");
    }

    /**
     * Teste le tri des formations par un champ donné (ici, "title").
     */
    public function testFindAllOrderBy(): void
    {
        $repository = $this->recupRepository();

        // Trie les formations par "title" dans l'ordre DESC
        $formations = $repository->findAllOrderBy("title", "DESC");

        // Vérifie que le titre de la première formation triée est "UML : Diagramme de classes"
        $this->assertEquals(
            "UML : Diagramme de classes",
            $formations[0]->getTitle(),
            "Le tri n'a pas retourné les résultats attendus."
        );
    }

    /**
     * Teste la récupération des formations les plus récentes.
     */
    public function testFindAllLasted(): void
    {
        $repository = $this->recupRepository();

        // Récupère les 2 formations les plus récentes
        $formations = $repository->findAllLasted(2);

        // Vérifie que la deuxième formation récente est "UML : Diagramme de classes"
        $this->assertEquals(
            "UML : Diagramme de classes",
            $formations[1]->getTitle(),
            "Les formations récentes ne sont pas correctes."
        );
    }

    /**
     * Teste la récupération des formations associées à une playlist spécifique.
     */
    public function testFindAllForOnePlaylist(): void
    {
        $repository = $this->recupRepository();

        // Récupère les formations associées à une playlist avec l'ID 1
        $formations = $repository->findAllForOnePlaylist(1);

        // Vérifie que le titre de la première formation est "Eclipse n°8 : Déploiementsss"
        $this->assertEquals(
            "Eclipse n°8 : Déploiementsss",
            $formations[0]->getTitle(),
            "Les formations associées à la playlist ne sont pas correctes."
        );
    }
}
