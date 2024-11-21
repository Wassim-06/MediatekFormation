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
 * Contrôleur pour la gestion des playlists dans la section administrateur.
 *
 * Ce contrôleur permet d'afficher, trier, rechercher, ajouter, modifier
 * et supprimer des playlists via des routes spécifiques aux administrateurs.
 *
 * @author emds
 */
class AdminPlaylistsController extends AbstractController
{
    /**
     * Référentiel pour accéder aux données des playlists.
     *
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * Référentiel pour accéder aux données des catégories.
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Référentiel pour accéder aux données des formations.
     *
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * Chemin du fichier Twig utilisé pour afficher les playlists.
     *
     * @var string
     */
    public $lien = "admin/admin.playlists.html.twig";

    /**
     * Constructeur du contrôleur.
     *
     * @param PlaylistRepository $playlistRepository Référentiel des playlists.
     * @param CategorieRepository $categorieRepository Référentiel des catégories.
     * @param FormationRepository $formationRepository Référentiel des formations.
     */
    public function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRepository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    /**
     * Affiche la liste des playlists.
     *
     * @Route('/admin/playlists', name='admin.playlists')
     * @return Response La réponse HTTP avec la vue des playlists.
     */
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();

        return $this->render($this->lien, [
            'playlists' => $playlists,
            'categories' => $categories,
        ]);
    }

    /**
     * Trie les playlists par un champ donné.
     *
     * @Route('/admin/playlists/tri/{champ}/{ordre}', name='admin.playlists.sort')
     * @param string $champ Le champ sur lequel trier.
     * @param string $ordre L'ordre de tri (ASC ou DESC).
     * @return Response La réponse HTTP avec les playlists triées.
     */
    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort(string $champ, string $ordre): Response
    {
        if ($champ === "name") {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        } elseif ($champ === "nbrdeformation") {
            $playlists = $this->playlistRepository->findAllOrderByNbrFormation($ordre);
        }

        $categories = $this->categorieRepository->findAll();

        return $this->render($this->lien, [
            'playlists' => $playlists,
            'categories' => $categories,
        ]);
    }

    /**
     * Recherche les playlists contenant une valeur donnée dans un champ.
     *
     * @Route('/admin/playlists/recherche/{champ}/{table}', name='admin.playlists.findallcontain')
     * @param string $champ Le champ à rechercher.
     * @param Request $request La requête contenant la valeur de recherche.
     * @param string $table (Optionnel) La table associée si le champ appartient à une autre entité.
     * @return Response La réponse HTTP avec les résultats de la recherche.
     */
    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain(string $champ, Request $request, string $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();

        return $this->render($this->lien, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table,
        ]);
    }

    /**
     * Supprime une playlist.
     *
     * @Route('/admin/playlists/delete/{id}', name='admin.playlists.delete')
     * @param int $id L'identifiant de la playlist à supprimer.
     * @return Response Une redirection vers la liste des playlists.
     */
    #[Route('/admin/playlists/delete/{id}', name: 'admin.playlists.delete')]
    public function delete(int $id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $formations = $this->formationRepository->findAllForOnePlaylist($id);

        if (\count($formations) === 0) {
            $this->playlistRepository->remove($playlist);
        } else {
            $this->addFlash('error', "Impossible de supprimer cette playlist car elle contient des formations.");
        }

        return $this->redirectToRoute("admin.playlists");
    }

    /**
     * Ajoute une nouvelle playlist.
     *
     * @Route('/admin/playlist/add', name='admin.playlist.add')
     * @param Request $request La requête contenant les données du formulaire.
     * @return Response La vue du formulaire ou une redirection si ajout réussi.
     */
    #[Route('/admin/playlist/add', name: 'admin.playlist.add')]
    public function add(Request $request): Response
    {
        $playlist = new Playlist();
        $playlistForm = $this->createForm(PlaylistType::class, $playlist);

        $playlistForm->handleRequest($request);
        if ($playlistForm->isSubmitted() && $playlistForm->isValid()) {
            $this->playlistRepository->add($playlist);

            return $this->redirectToRoute("admin.playlists");
        }

        return $this->render("admin/admin.playlist.add.html.twig", [
            'playlistform' => $playlistForm->createView(),
        ]);
    }

    /**
     * Modifie une playlist existante.
     *
     * @Route('/admin/playlist/edit/{id}', name='admin.playlist.edit')
     * @param int $id L'identifiant de la playlist à modifier.
     * @param Request $request La requête contenant les données du formulaire.
     * @return Response La vue du formulaire ou une redirection si modification réussie.
     */
    #[Route('/admin/playlist/edit/{id}', name: 'admin.playlist.edit')]
    public function edit(int $id, Request $request): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $playlistForm = $this->createForm(PlaylistType::class, $playlist);

        $playlistForm->handleRequest($request);
        if ($playlistForm->isSubmitted() && $playlistForm->isValid()) {
            $this->playlistRepository->add($playlist);

            return $this->redirectToRoute("admin.playlists");
        }

        return $this->render("admin/admin.playlist.add.html.twig", [
            'playlistform' => $playlistForm->createView(),
            'playlist' => $playlist,
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
}
