<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\admin;

use App\Entity\Niveau;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminNiveauController
 *
 * @author oreoc
 */
class AdminNiveauController extends AbstractController {
    /**
    *
    * @var NiveauRepository 
    */
    private $repository;
    
    /**
     *
     * @var EntityManagerInterface 
     */
    private $om;
   
    /**
     * 
     * @param NiveauRepository $repository
     * @param EntityManagerInterface $om
     */
    function __construct(NiveauRepository $repository, EntityManagerInterface $om) {
        $this->repository = $repository;
        $this->om = $om;
    }
    
    /**
     * @Route("/admin/niveaux", name="admin.niveaux")
     * @return Response
     */
    public function index() : Response{
        $niveaux = $this->repository->findAll();
        return $this->render("admin/admin.niveaux.html.twig", [
            'niveaux' => $niveaux
        ]);
    }
    
    /**
     * @Route("/admin/niveau/suppr/{id}", name="admin.niveau.suppr")
     * @param Niveau $niveau
     * @return Response
     */
    public function suppr(Niveau $niveau): Response{
        $this->om->remove($niveau);
        $this->om->flush();
        return $this->redirectToRoute('admin.niveaux');
    }     

    /**
     * @Route("/admin/niveau/ajout", name="admin.niveau.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response {
        $nomNiveau = $request->get("nom");
        $niveau = new Niveau();
        $niveau->setNom($nomNiveau);
        $this->om->persist($niveau);
        $this->om->flush();
        return $this->redirectToRoute("admin.niveaux");
    }
}
