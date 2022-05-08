<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
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
class AdminFormationController extends AbstractController{
    /**
    *
    * @var FormationRepository 
    */
    private $repository;
    
    /**
     *
     * @var EntityManagerInterface 
     */
    private $om;
   
    /**
     * 
     * @param FormationRepository $repository
     * @param EntityManagerInterface $om
     */
    function __construct(FormationRepository $repository, EntityManagerInterface $om) {
        $this->repository = $repository;
        $this->om = $om;
    }
    
    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index() : Response{
        $formations = $this->repository->findAllOrderBy("publishedAt", "DESC");
        return $this->render("admin/admin.formations.html.twig", [
            'formations' => $formations
        ]);
    }
    
    /**
     * @Route("/admin/formation/suppr/{id}", name="admin.formation.suppr")
     * @param Formation $formation
     * @return Response
     */
    public function suppr(Formation $formation): Response{
        $this->om->remove($formation);
        $this->om->flush();
        return $this->redirectToRoute('admin.formations');
    } 
    
    /**
     * @Route("/admin/tri/{champ}/{ordre}", name="admin.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        $formations = $this->repository->findAllOrderBy($champ, $ordre);
        return $this->render("admin/admin.formations.html.twig", [
           'formations' => $formations
        ]);
    }  
    
    /**
     * @Route("/admin/recherche/nom/{champ}", name="admin.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $valeur = $request->get("recherche");
            $formations = $this->repository->findByContainValue($champ, $valeur);
            return $this->render("admin/admin.formations.html.twig", [
                'formations' => $formations
            ]);
        }
        return $this->redirectToRoute("admin.formations");
    } 
    
    /**
     * @Route("/admin/edit/{id}", name="admin.formation.edit")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    public function edit(Formation $formation, Request $request): Response{
        $formFormation = $this->createForm(FormationType::class, $formation);

        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->om->flush();
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render("admin/admin.formation.edit.html.twig", [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
    
    /**
     * @Route("/admin/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $formation = new Formation();
        $formformation = $this->createForm(FormationType::class, $formation);

        $formformation->handleRequest($request);
        if($formformation->isSubmitted() && $formformation->isValid()){
            $this->om->persist($formation);
            $this->om->flush();
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render("admin/admin.formation.ajout.html.twig", [
            'formation' => $formation,
            'formformation' => $formformation->createView()
        ]);
    }
}
