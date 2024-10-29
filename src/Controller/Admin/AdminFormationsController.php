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
 * Controleur des formations
 *
 * @author wa2s
 */
class AdminFormationsController extends AbstractController
{

    /**
     *
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     *
     * @var string
     */
    public $lien = "admin/admin.formations.html.twig";

    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

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

    #[Route('/admin/formation/delete/{id}', name: 'admin.formation.delete')]
    public function delete(int $id): Response
    {
        $formation = $this->formationRepository->find($id);
        $this->formationRepository->remove($formation);
        return $this->redirectToRoute('admin.formations');
    }

    #[Route('/admin/formation/add', name: 'admin.formation.add')]
    public function add(Request $request): Response
    {
        $formation = new Formation();
        $formationForm = $this->createForm(FormationsFormType::class, $formation);

        //On traite la requête
        $formationForm->handleRequest($request);
        if ($formationForm->isSubmitted() && $formationForm->isValid()) {
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render('admin/admin.formation.add.html.twig', [
            'formationForm' => $formationForm->createView()
        ]);
    }

    #[Route('/admin/formation/edit/{id}', name: 'admin.formation.edit')]
    public function edit(int $id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);
        $formationForm = $this->createForm(FormationsFormType::class, $formation);

        //On traite la requête
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
