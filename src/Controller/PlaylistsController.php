<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Ce contrôleur gère les opérations liées aux playlists :
 * - Affichage de la liste des playlists.
 * - Tri et recherche sur les playlists.
 * - Affichage d'une playlists spécifique.
 *
 * @author Wa2s
 */
class PlaylistsController extends AbstractController
{

    /**
     * Référentiel pour accéder aux données des playlists
     *
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * Référentiel pour accéder aux données des formations
     *
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * Référentiel pour accéder aux données des catégories
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Chemin du fichier Twig utilisé pour afficher les playlists
     *
     * @var string
     */
    public $lien = "pages/playlists.html.twig";

    /**
     * Constructeur du contrôleur
     *
     * @param PlaylistRepository $playlistRepository Référentiel des Playlists
     * @param CategorieRepository $categorieRepository Référentiel des catégories
     * @param FormationRepository $formationRepository Référentiel des Formations
     */
    public function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRespository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }

    /**
     * Affiche la liste des Playlists
     *
     * @Route('/playlists', name: 'playlists')
     * @return Response La réponse HTTP avec la vue, $playlists et $categories.
     */
    #[Route('/playlists', name: 'playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->lien, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Affiche la liste des playlists triées sur un champ et un ordre.
     *
     * @param string $champ Champ sur lequel trier
     * @param string $ordre Ordre de tri (ASC ou DESC)
     * @Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')
     * @return Response La réponse HTTP avec la vue triée, $playlists et $categories.
     */
    #[Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')]
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

    /**
     * Affiche la liste des playlists filtrées sur un champ et une valeur.
     *
     * @Route('/playlists/recherche/{champ}/{ordre}', name: 'playlists.findallcontain')
     * @param string $champ Champ sur lequel trier.
     * @param Request $request La requête contenant la valeur de recherche.
     * @param string $table (Optionnel) La table concernée.
     * @return Response La réponse HTTP avec la vue triée, $playlists et $categories.
     */
    #[Route('/playlists/recherche/{champ}/{table}', name: 'playlists.findallcontain')]
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

    /**
     * Affiche la page d'une playlist.
     *
     * @Route("/playlists/playlist/{id}", name: "playlist.showone")
     * @param int $id L'id de la playlist.
     * @return Response La réponse HTTP avec la vue de la playlist.
     */
    #[Route('/playlists/playlist/{id}', name: 'playlists.showone')]
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
}
