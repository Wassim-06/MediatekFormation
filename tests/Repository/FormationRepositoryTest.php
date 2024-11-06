<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormationRepositoryTest extends KernelTestCase
{

    public function recupRepository(): FormationRepository
    {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }

    public function testNbFormations()
    {
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(9, $nbFormations);
    }

    public function newFormation(): Formation
    {
        $formation = (new Formation())
            ->setTitle("Test")
            ->setVideoId("123")
            ->setPublishedAt(new \DateTime("2025-01-04 17:00:12"));
        return $formation;
    }

    public function testAddFormation()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "erreur lors de l'ajout");
    }

    public function testRemoveFormation()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "erreur lors de la suppresion");
    }

    public function testFindByContainValue()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValue("title", "Test");
        $this->assertEquals("Test", $formations[0]->getTitle());
    }

    public function testFindAllOrderBy()
    {
        $repository = $this->recupRepository();
        $formations = $repository->findAllOrderBy("title", "DESC");
        $this->assertEquals("UML : Diagramme de classes", $formations[0]->getTitle());
    }

    public function testFindAllLasted()
    {
        $repository = $this->recupRepository();
        $formations = $repository->findAllLasted(2);
        $this->assertEquals("UML : Diagramme de classes", $formations[1]->getTitle());
    }

    public function testFindAllForOnePlaylist()
    {
        $repository = $this->recupRepository();
        $formations = $repository->findAllForOnePlaylist(1);
        $this->assertEquals("Eclipse n°8 : Déploiementsss", $formations[0]->getTitle());
    }
}
