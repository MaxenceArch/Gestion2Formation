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

class InscriptionController extends AbstractController
{
    #[Route('/inscription{formationId}', name: 'inscriptionEmp')]
    public function inscriptionAction(Request $request, $formationId, ManagerRegistry $doctrine, SessionInterface $session): Response
    {
        // Récupère l'ID de l'employé depuis la session
        $employeId = $session->get('id');
        // Vérifie si l'ID de l'employé est présent dans la session
        if (!$employeId) {
            throw new \Exception('L utilisateur n est pas authentifié.');
        }
        // Récupère l'objet Employe depuis la base de données
        $employe = $doctrine->getRepository(Employe::class)->find($employeId);

        // Récupère la formation
        $formation = $doctrine->getRepository(Formation::class)->find($formationId);

        // Vérification si l'employé est déjà inscrit à la formation
        $inscriptionExistante = $doctrine->getRepository(Inscription::class)
            ->findOneBy(['lemploye' => $employe, 'laFormation' => $formation]);

        // Si l'employé n'est pas encore inscrit, alors il peut s'inscrire
        if (!$inscriptionExistante) {
            $inscription = new Inscription();
            $inscription->setLemploye($employe);
            $inscription->setLaFormation($formation);
            $inscription->setStatut("En attente"); // Modifier le statut ici

            // Validation de l'inscription
            $entityManager = $doctrine->getManager();
            $entityManager->persist($inscription);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription en attente de validation.');
        } else{
            $this->addFlash('warning', 'Vous avez déja effectué une tentative d\'inscription sur cette formation.');
        }

        return $this->redirectToRoute('app_affEmp');
    }

    #[Route('/refuser/{id}',name:'app_refuser_inscription')]
    public function refuserInscription($id,ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $inscription = $doctrine->getManager()->getRepository(Inscription::class)->find($id);

        $inscription->setStatut('Refusé');
        $entityManager->flush();
        return $this->redirectToRoute('app_liste_inscription_admin');
    }

    #[Route('/accepter/{id}',name:'app_accepter_inscription')]
    public function acceptéInscription($id,ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $inscription = $doctrine->getManager()->getRepository(Inscription::class)->find($id);

        $inscription->setStatut('Accepté');
        $entityManager->flush();
        return $this->redirectToRoute('app_liste_inscription_admin');
    }

    #[Route('/afficheListeInscriptionAdmin',name:'app_liste_inscription_admin')]
    public function afficheLesInscriptionsAdmin(ManagerRegistry $doctrine)
    {
        $inscriptions = $doctrine->getManager()->getRepository(Inscription::class)->findAll();
        
        if(!$inscriptions){
            $message="Aucune inscriptions en attente";
        }
        else{
            $message=null;
        }
        return $this->render('inscriptions/inscriptionAdmin.html.twig',array(
            'ensInscription'=>$inscriptions,
            'message'=>$message));
    }


    #[Route('/afficheForma1Emp',name: 'app_affFormaEmp1')]
    public function afficheForma1Emp(ManagerRegistry $doctrine,SessionInterface $session)
    {
        $inscriptions = $doctrine->getManager()->getRepository(Inscription::class)->findAll();
        $employeId = $session->get('id');
        if(!$inscriptions){
            $message="Aucune inscriptions en attente";
        }
        else{
            $message=null;
        }
        return $this->render('inscriptions/inscriptionD1Emp.html.twig',array(
            'ensInscription'=>$inscriptions,
            'employeId' => $employeId,
            'message'=>$message));
    }

    #[Route('/afficheListeInscriptionAccepte',name:'app_liste_inscription_accepte')]
    public function afficheLesInscriptionsAccepte(ManagerRegistry $doctrine)
    {
        $inscriptions = $doctrine->getManager()->getRepository(Inscription::class)->findAll();
        
        if(!$inscriptions){
            $message="Aucune inscriptions acceptée";
        }
        else{
            $message=null;
        }
        return $this->render('inscriptions/inscriptionAccepte.html.twig',array(
            'ensInscription'=>$inscriptions,
            'message'=>$message));
    }

    #[Route('/afficheListeInscriptionRefuse',name:'app_liste_inscription_refuse')]
    public function afficheLesInscriptionsRefuse(ManagerRegistry $doctrine)
    {
        $inscriptions = $doctrine->getManager()->getRepository(Inscription::class)->findAll();
        
        if(!$inscriptions){
            $message="Aucune inscriptions refusée";
        }
        else{
            $message=null;
        }
        return $this->render('inscriptions/inscriptionRefuse.html.twig',array(
            'ensInscription'=>$inscriptions,
            'message'=>$message));
    }

    #[Route('/affInscriptionParEmploye/{id}',name:'app_aff_inscr_par_Emp')]
    public function afficheInscriptionParEmp($id,ManagerRegistry $doctrine)
    {
        $inscriptions = $doctrine->getManager()->getRepository(Inscription::class)->findAll();

        if(!$inscriptions){
            $message="Aucune inscriptions ";
        }
        else{
            $message=null;
        }

        return $this->render('inscriptions/inscriptionParEmp.html.twig',array(
            'ensInscription'=>$inscriptions,
            'message'=>$message,
            'id'=>$id));
    }
}