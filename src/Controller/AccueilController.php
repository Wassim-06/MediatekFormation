<?php

namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Ce contrôleur permet d'envoyer les donnée nécessaire pour afficher la page d'accueil et les cgu
 *
 * @author Wa2s
 */
class AccueilController extends AbstractController
{

    /**
     * Référentiel pour accèder aux données des formations
     *
     * @var FormationRepository
     */
    private $repository;

    /**
     * Constructeur du contrôleur
     *
     * @param FormationRepository $repository Référentiel des formations
     */
    public function __construct(FormationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Affiche la page d'accueil avec les deux dérnières formations
     *
     * @Route('/', name: 'accueil')
     * @return Response La réponse HTTP avec la vue
     */
    #[Route('/', name: 'accueil')]
    public function index(): Response
    {
        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/accueil.html.twig", [
            'formations' => $formations
        ]);
    }

    /**
     * Affiche la page des Conditions Générales d'utilisation
     *
     * @Route('/cgu', name: 'cgu')
     * @return Response La réponse HTTP avec la vue
     */
    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response
    {
        return $this->render("pages/cgu.html.twig");
    }
}
