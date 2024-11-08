<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Classe de tests pour le repository CategorieRepository.
 *
 * Cette classe vérifie les fonctionnalités suivantes :
 * - Compte des catégories.
 * - Ajout et suppression de catégories.
 * - Récupération des catégories liées à une playlist spécifique.
 */
class CategorieRepositoryTest extends KernelTestCase
{
    /**
     * Récupère une instance du repository CategorieRepository.
     *
     * @return CategorieRepository
     */
    public function recupRepository(): CategorieRepository
    {
        self::bootKernel(); // Lance le noyau Symfony
        $repository = self::getContainer()->get(CategorieRepository::class); // Récupère le repository
        return $repository;
    }

    /**
     * Teste le nombre total de catégories dans la base de données.
     */
    public function testNbCategories(): void
    {
        $repository = $this->recupRepository();

        // Compte toutes les catégories dans la base
        $nbCategories = $repository->count([]);

        // Vérifie que le nombre correspond au nombre attendu (9 ici)
        $this->assertEquals(9, $nbCategories, "Le nombre de catégories ne correspond pas.");
    }

    /**
     * Crée une nouvelle instance de la classe Categorie pour les tests.
     *
     * @return Categorie
     */
    public function newCategorie(): Categorie
    {
        $categorie = (new Categorie())
            ->setName("Test"); // Définir un nom temporaire
        return $categorie;
    }

    /**
     * Teste l'ajout d'une nouvelle catégorie.
     */
    public function testAddCategorie(): void
    {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();

        // Compte les catégories avant l'ajout
        $nbCategories = $repository->count([]);

        // Ajoute une nouvelle catégorie
        $repository->add($categorie, true);

        // Vérifie que le nombre de catégories a augmenté de 1
        $this->assertEquals(
            $nbCategories + 1,
            $repository->count([]),
            "Erreur lors de l'ajout d'une catégorie."
        );
    }

    /**
     * Teste la suppression d'une catégorie.
     */
    public function testRemoveCategorie(): void
    {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();

        // Ajoute une catégorie pour la supprimer ensuite
        $repository->add($categorie, true);

        // Compte les catégories avant la suppression
        $nbCategories = $repository->count([]);

        // Supprime la catégorie ajoutée
        $repository->remove($categorie, true);

        // Vérifie que le nombre de catégories a diminué de 1
        $this->assertEquals(
            $nbCategories - 1,
            $repository->count([]),
            "Erreur lors de la suppression d'une catégorie."
        );
    }

    /**
     * Teste la récupération des catégories associées à une playlist.
     */
    public function testFindAllForOnePlaylist(): void
    {
        $repository = $this->recupRepository();

        // Récupère les catégories associées à une playlist avec l'ID 1
        $categories = $repository->findAllForOnePlaylist(1);

        // Vérifie que la première catégorie est "Java"
        $this->assertEquals(
            "Java",
            $categories[0]->getName(),
            "Erreur : La première catégorie associée à la playlist 1 n'est pas 'Java'."
        );
    }
}
