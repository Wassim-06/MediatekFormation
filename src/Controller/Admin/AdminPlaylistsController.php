<?php

namespace App\Controller\Admin;

use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use App\Form\PlaylistType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 * @author emds
 */
class AdminPlaylistsController extends AbstractController
{

    /**
     *
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     *
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     *
     * @var string
     */
    public $lien = "admin/admin.playlists.html.twig";

    public function __construct(PlaylistRepository $playlistRepository, CategorieRepository $categorieRepository, FormationRepository $formationRepository)
    {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->lien, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response
    {
        if ($champ == "name") {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        }
        if ($champ == "nbrdeformation") {
            $playlists = $this->playlistRepository->findAllOrderByNbrFormation($ordre);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->lien, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->lien, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/admin/playlists/playlist/{id}', name: 'admin.playlists.showone')]
    public function showOne($id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/playlist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations,
        ]);
    }

    #[Route('/admin/playlists/delete/{id}', name: 'admin.playlists.delete')]
    public function delete($id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $nbrdeforma = $this->formationRepository->findAllForOnePlaylist($playlist);
        if (\count($nbrdeforma) == 0) {
            $this->playlistRepository->remove($playlist);
        }
        return $this->redirectToRoute("admin.playlists");
    }

    #[Route('/admin/playlist/add', name: 'admin.playlist.add')]
    public function add(Request $request): Response
    {
        $playlist = new Playlist();
        $playlistform = $this->createForm(PlaylistType::class, $playlist);

        $playlistform->handleRequest($request);
        if ($playlistform->isSubmitted() && $playlistform->isValid()) {
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute("admin.playlists");
        }
        return $this->render("admin/admin.playlist.add.html.twig", [
            'playlistform' => $playlistform->createView()
        ]);
    }

    #[Route('/admin/playlist/edit/{id}', name: 'admin.playlist.edit')]
    public function edit(Request $request, int $id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $playlistform = $this->createForm(PlaylistType::class, $playlist);

        $playlistform->handleRequest($request);
        if ($playlistform->isSubmitted() && $playlistform->isValid()) {
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute("admin.playlists");
        }
        return $this->render("admin/admin.playlist.add.html.twig", [
            'playlistform' => $playlistform->createView(),
            'playlist' => $playlist
        ]);
    }
}
