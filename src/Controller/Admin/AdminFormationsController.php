<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Form\FormationsFormType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour la gestion des formations dans la section administrateur.
 *
 * Ce contrôleur permet d'afficher, ajouter, modifier, supprimer et trier
 * les formations accessibles via les routes dédiées aux administrateurs.
 *
 * @author wa2s
 */
class AdminFormationsController extends AbstractController
{
    /**
     * Référentiel pour accéder aux données des formations.
     *
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * Référentiel pour accéder aux données des catégories.
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Chemin du fichier Twig utilisé pour afficher les formations.
     *
     * @var string
     */
    public $lien = "admin/admin.formations.html.twig";

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
     * Affiche la liste des formations.
     *
     * @Route('/admin/formations', name='admin.formations')
     * @return Response La réponse HTTP avec la vue des formations.
     */
    #[Route('/admin/formations', name: 'admin.formations')]
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
     * Trie les formations selon un champ et un ordre spécifiés.
     *
     * @Route('/admin/formations/tri/{champ}/{ordre}/{table}', name='admin.formations.sort')
     * @param string $champ Le champ sur lequel trier.
     * @param string $ordre L'ordre de tri (ASC ou DESC).
     * @param string $table (Optionnel) La table associée si le champ appartient à une autre entité.
     * @return Response La réponse HTTP avec la vue triée.
     */
    #[Route('/admin/formations/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')]
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
     * Recherche les formations contenant une valeur donnée dans un champ.
     *
     * @Route('/admin/formations/recherche/{champ}/{table}', name='admin.formations.findallcontain')
     * @param string $champ Le champ à rechercher.
     * @param Request $request La requête contenant la valeur à rechercher.
     * @param string $table (Optionnel) La table associée si le champ appartient à une autre entité.
     * @return Response La réponse HTTP avec les résultats de la recherche.
     */
    #[Route('/admin/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
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
     * Supprime une formation.
     *
     * @Route('/admin/formation/delete/{id}', name='admin.formation.delete')
     * @param int $id L'identifiant de la formation à supprimer.
     * @return Response Une redirection vers la liste des formations.
     */
    #[Route('/admin/formation/delete/{id}', name: 'admin.formation.delete')]
    public function delete(int $id): Response
    {
        $formation = $this->formationRepository->find($id);
        $this->formationRepository->remove($formation);
        return $this->redirectToRoute('admin.formations');
    }

    /**
     * Ajoute une nouvelle formation.
     *
     * @Route('/admin/formation/add', name='admin.formation.add')
     * @param Request $request La requête contenant les données du formulaire.
     * @return Response La vue du formulaire ou une redirection si ajout réussi.
     */
    #[Route('/admin/formation/add', name: 'admin.formation.add')]
    public function add(Request $request): Response
    {
        $formation = new Formation();
        $formationForm = $this->createForm(FormationsFormType::class, $formation);

        // Traite la requête
        $formationForm->handleRequest($request);
        if ($formationForm->isSubmitted() && $formationForm->isValid()) {
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render('admin/admin.formation.add.html.twig', [
            'formationForm' => $formationForm->createView()
        ]);
    }

    /**
     * Modifie une formation existante.
     *
     * @Route('/admin/formation/edit/{id}', name='admin.formation.edit')
     * @param int $id L'identifiant de la formation à modifier.
     * @param Request $request La requête contenant les données du formulaire.
     * @return Response La vue du formulaire ou une redirection si modification réussie.
     */
    #[Route('/admin/formation/edit/{id}', name: 'admin.formation.edit')]
    public function edit(int $id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);
        $formationForm = $this->createForm(FormationsFormType::class, $formation);

        // Traite la requête
        $formationForm->handleRequest($request);
        if ($formationForm->isSubmitted() && $formationForm->isValid()) {
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render('admin/admin.formation.edit.html.twig', [
            'formationForm' => $formationForm->createView(),
            'formation' => $formation
        ]);
    }
}
