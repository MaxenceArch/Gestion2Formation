<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Formation;
use App\Form\FormationType;
use App\Entity\Inscription;
use App\Entity\Employe;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class EmpServiceFormationController extends AbstractController
{

    #[Route('/emp/service/formation', name: 'app_emp_service_formation')]
    public function index(): Response
    {
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'EmpServiceFormationController',
        ]);
    }

    #[Route('/suppFormation/{id}',name:'app_formation_sup')]
    public function suppFormation($id,ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $formation = $doctrine->getManager()->getRepository(Formation::class)->find($id);
        $entityManager->remove($formation);
        $entityManager->flush();
        return $this->redirectToRoute('app_aff');
    }

    #[Route('/modifFormation/{id}',name: 'app_formation_modifier')]
    public function modiFormation($id,Request $request,ManagerRegistry $doctrine)
    {
        $formation = $doctrine->getRepository(Formation::class)->find($id);
        if ($formation == null) {
            $formation = new Formation();
        }
        $form = $this->createForm(FormationType::class, $formation);
        //récupération de la requête
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($formation);
            $em->flush();
            return $this->redirectToRoute('app_aff');
        }
        return $this->render('formation/editer.html.twig',array('form'=>$form->createView()));
    }

    
    #[Route('/afficheLesFormations',name: 'app_aff')]
    public function afficheLesFormations(ManagerRegistry $doctrine)
    {
        $formations = $doctrine->getManager()->getRepository(Formation::class)->findAll();
        if (!$formations){
            $message ="Pas de formations";
        }
        else{
            $message= null;
        }
        return $this->render('formation/listeFormation.html.twig',array(
            'ensFormation' => $formations, 
            'message' => $message));
    }

    #[Route('/afficheLesFormationsEmp',name: 'app_affEmp')]
    public function afficheLesFormationsEmp(ManagerRegistry $doctrine,SessionInterface $session)
    {
        $formations = $doctrine->getManager()->getRepository(Formation::class)->findAll();
        $inscriptions = $doctrine->getManager()->getRepository(Formation::class)->findAll();
        $employeId = $session->get('id');
        if (!$formations){
            $message ="Pas de formations";
        }
        else{
            $message= null;
        }
        return $this->render('formation/listeFormationEmp.html.twig',array(
        'ensFormation' => $formations,
        'message' => $message,
        'employeId' => $employeId,
        'ensInscription' => $inscriptions));
    }

    #[Route('/afficheLesEmploye',name: 'app_affEmploye')]
    public function afficheLesEmploye(ManagerRegistry $doctrine,SessionInterface $session)
    {
        $employes = $doctrine->getManager()->getRepository(Employe::class)->findAll();
        $inscriptions = $doctrine->getManager()->getRepository(Formation::class)->findAll();
        $employeId = $session->get('id');
        if (!$employes){
            $message ="Pas d'employés";
        }
        else{
            $message= null;
        }
        return $this->render('emplambda/listeEmploye.html.twig',array(
        'ensEmploye' => $employes,
        'message' => $message,
        'employeId' => $employeId,
        'ensInscription' => $inscriptions));
    }



    #[Route('/ajoutFormation',name: 'app_formation_ajouter')]
    public function ajoutFormation(Request $request,ManagerRegistry $doctrine, $formation= null)
    {
        if ($formation == null) {
            $formation = new Formation();
        }
        $form = $this->createForm(FormationType::class, $formation);
        //récupération de la requête
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($formation);
            $em->flush();
            return $this->redirectToRoute('app_aff');
        }
        return $this->render('formation/editer.html.twig',array('form'=>$form->createView()));
    }
}
