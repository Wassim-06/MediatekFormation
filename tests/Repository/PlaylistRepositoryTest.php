<?php

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlaylistRepositoryTest extends KernelTestCase
{

    public function recupRepository(): PlaylistRepository
    {
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }

    public function testNbPlaylists()
    {
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->count([]);
        $this->assertEquals(28, $nbPlaylists);
    }

    public function newPlaylist(): Playlist
    {
        $playlist = (new Playlist())
            ->setName("Test")
            ->setNbrdeformation(0);
        return $playlist;
    }

    public function testAddPlaylist()
    {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository->add($playlist, true);
        $this->assertEquals($nbPlaylists + 1, $repository->count([]), "erreur lors de l'ajout");
    }

    public function testRemovePlaylist()
    {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $nbPlaylists = $repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylists - 1, $repository->count([]), "erreur lors de la suppresion");
    }

    public function testFindAllOrderByName()
    {
        $repository = $this->recupRepository();
        $nbpaylits = $repository->findAllOrderByName("DESC");
        $this->assertEquals($nbpaylits[2]->getName(), "Testadd");
    }

    public function testFindAllOrderByNbrFormation()
    {
        $repository = $this->recupRepository();
        $nbplaylists = $repository->findAllOrderByNbrFormation("ASC");
        $this->assertEquals("Cours Modèle relationnel et MCD", $nbplaylists[3]->getName());
    }

    public function testFindByContainValue()
    {
        $repository = $this->recupRepository();
        $nbplaylists = $repository->findByContainValue("name", "Java");
        $this->assertEquals("POO TP Java", $nbplaylists[1]->getName());
    }
}
