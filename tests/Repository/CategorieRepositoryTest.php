<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategorieRepositoryTest extends KernelTestCase
{

    public function recupRepository(): CategorieRepository
    {
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }

    public function testNbCategories()
    {
        $repository = $this->recupRepository();
        $nbCategories = $repository->count([]);
        $this->assertEquals(9, $nbCategories);
    }

    public function newCategorie(): Categorie
    {
        $categorie = (new Categorie())
            ->setName("Test");
        return $categorie;
    }

    public function testAddCategorie()
    {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->add($categorie, true);
        $this->assertEquals($nbCategories + 1, $repository->count([]), "erreur lors de l'ajout");
    }

    public function testRemoveCategorie()
    {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $nbCategories = $repository->count([]);
        $repository->remove($categorie, true);
        $this->assertEquals($nbCategories - 1, $repository->count([]), "erreur lors de la suppresion");
    }

    public function testFindAllForOnePlaylist()
    {
        $repository = $this->recupRepository();
        $categories = $repository->findAllForOnePlaylist(1);
        $this->assertEquals("Java", $categories[0]->getName());
    }
}
