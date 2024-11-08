<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Ce contrôleur gère les opérations liées aux formations :
 * - Affichage de la liste des formations.
 * - Tri et recherche sur les formations.
 * - Affichage d'une formation spécifique.
 *
 * @author Wa2s
 */
class FormationsController extends AbstractController
{

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
     * Chemin du fichier Twig utilisé pour afficher les formations.
     *
     * @var string
     */
    public $lien = "pages/formations.html.twig";

    /**
     * Constructeur du contrôleur.
     *
     * @param FormationRepository $formationRepository Référentiel des formations.
     * @param CategorieRepository $categorieRepository Référentiel des catégories.
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Affiche la liste des formations et des catégories.
     *
     * @Route("/formations", name="formations")
     * @return Response La réponse HTTP avec la vue.
     */
    #[Route('/formations', name: 'formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->lien, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Affiche la liste des formations triées sur un champ et un ordre.
     *
     * @Route("/formations/tri/{champ}/{ordre}/{table}", name: "formations.sort")
     * @param string $champ Le champ sur lequel trier.
     * @param string $ordre L'ordre de tri (ASC ou DESC).
     * @param string $table (Optionnel) La table concernée.
     * @return Response La réponse HTTP avec la vue triée.
     */
    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table = ""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->lien, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Affiche la liste des formations filtrées sur un valeur et un champ.
     *
     * @Route("/formations/recherche/{champ}/{table}", name: "formations.findallcontain")
     * @param string $champ Le champ à rechercher.
     * @param Request $request La requête contenant la valeur de recherche.
     * @param string $table (Optionnel) La table concernée.
     * @return Response La réponse HTTP avec les résultats de la recherche.
     */
    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->lien, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Affiche la page d'une formation.
     *
     * @Route("/formations/formation/{id}", name: "formations.showone")
     * @param int $id L'id de la formation.
     * @return Response La réponse HTTP avec la vue de la formation.
     */
    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne($id): Response
    {
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);
    }
}
