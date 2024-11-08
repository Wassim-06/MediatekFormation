<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour la gestion des catégories dans la section administrateur.
 *
 * Ce contrôleur permet de gérer les catégories (affichage, ajout, suppression)
 * via les routes accessibles aux administrateurs.
 */
class AdminCategoriesController extends AbstractController
{
    /**
     * Référentiel pour accéder aux données des catégories.
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Constructeur du contrôleur.
     *
     * @param CategorieRepository $categorieRepository Référentiel des catégories.
     */
    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Affiche la liste des catégories.
     *
     * @Route('/admin/categories', name='admin.categories')
     * @return Response La réponse HTTP avec la vue des catégories.
     */
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response
    {
        // Récupération de toutes les catégories
        $categories = $this->categorieRepository->findAll();

        // Rendu du template avec la liste des catégories
        return $this->render("admin/admin.categories.html.twig", [
            'categories' => $categories
        ]);
    }

    /**
     * Supprime une catégorie.
     *
     * Si la catégorie est associée à des formations, elle ne peut pas être supprimée.
     *
     * @Route('/admin/categorie/delete/{id}', name='admin.categorie.delete')
     * @param int $id L'identifiant de la catégorie à supprimer.
     * @return Response Une redirection vers la liste des catégories.
     */
    #[Route('/admin/categorie/delete/{id}', name: 'admin.categorie.delete')]
    public function delete(int $id): Response
    {
        // Récupération de la catégorie par son identifiant
        $categorie = $this->categorieRepository->find($id);

        // Vérifie si la catégorie est associée à des formations
        $nbrdeformations = $categorie->getFormations();
        if ($nbrdeformations->isEmpty()) {
            // Supprime la catégorie si elle n'est pas liée à des formations
            $this->categorieRepository->remove($categorie);
        } else {
            // Ajoute un message d'erreur si la catégorie est liée à des formations
            $this->addFlash('error', "Cette catégorie ne peut pas être supprimée car elle est rattachée à des formations.");
        }

        // Redirection vers la liste des catégories
        return $this->redirectToRoute("admin.categories");
    }

    /**
     * Ajoute une nouvelle catégorie.
     *
     * Si une catégorie avec le même nom existe déjà, elle ne peut pas être ajoutée.
     *
     * @Route('/admin/categorie/add', name='admin.categorie.add')
     * @param Request $request La requête contenant les données du formulaire.
     * @return Response Une redirection vers la liste des catégories.
     */
    #[Route('/admin/categorie/add', name: 'admin.categorie.add')]
    public function add(Request $request): Response
    {
        // Récupération du nom de la catégorie depuis la requête
        $name = $request->request->get('name');

        // Vérifie si une catégorie avec le même nom existe déjà
        if ($this->categorieRepository->findBy(['name' => $name])) {
            // Ajoute un message d'erreur si la catégorie existe déjà
            $this->addFlash('error1', "Cette catégorie ne peut pas être ajoutée car elle existe déjà.");
        } else {
            // Crée une nouvelle catégorie et l'ajoute à la base de données
            $categorie = new Categorie();
            $categorie->setName($name);
            $this->categorieRepository->add($categorie);
        }

        // Redirection vers la liste des catégories
        return $this->redirectToRoute("admin.categories");
    }
}
