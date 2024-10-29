<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController
{

    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.categories.html.twig", [
            'categories' => $categories
        ]);
    }
    #[Route('/admin/categorie/delete/{id}', name: 'admin.categorie.delete')]
    public function delete(int $id): Response
    {
        $categorie = $this->categorieRepository->find($id);
        $nbrdeformations = $categorie->getFormations();
        if ($nbrdeformations->isEmpty()) {
            $this->categorieRepository->remove($categorie);
        } else {
            $this->addFlash('error', "Cette catégorie ne peut pas être supprimée car elle est rattachée à des formations.");
        }
        return $this->redirectToRoute("admin.categories");
    }
    #[Route('/admin/categorie/add', name: 'admin.categorie.add')]
    public function add(Request $request): Response
    {
        $name = $request->request->get('name');

        if ($this->categorieRepository->findBy(['name' => $name])) {
            $this->addFlash('error1', "Cette catégorie ne peut pas être ajouter car elle est existe déjà.");
        } else {
            $categorie = new Categorie();
            $categorie->setName($name);
            $this->categorieRepository->add($categorie);
        }
        return $this->redirectToRoute("admin.categories");
    }
}
