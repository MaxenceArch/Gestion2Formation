<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Employe;
use App\Entity\Inscription;
use App\Entity\Formation;
use App\Repository\InscriptionRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EmpInscriptionType;
use Doctrine\ORM\EntityManagerInterface;




class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(): Response
    {
        return $this->render('recherche/index.html.twig', [
            'controller_name' => 'RechercheController',
        ]);
    }

    
    #[Route('/rechercheBy', name: 'app_recherche_findBy')]
    
    public function rechercheFindByAction(ManagerRegistry $doctrine)
    {
        $employes=$doctrine->getRepository(Employe::class)->findBy(['nom'=>'castaing','statut'=>'0']);
        return $this->render('recherche/employe.html.twig', array('ensEmployes'=>$employes, 'nom'=>'castaing','statut'=>'0'));   
    }


    #[Route('/rechercheInscription', name: 'app_recherche_inscr')]
    public function rechercheFindInscriptionByAction(ManagerRegistry $doctrine)
    {
        $inscription=$doctrine->getRepository(Inscription::class)->rechInscriptionEmploye('Doe','John');
        //var_dump($inscription);
        //exit;
        return $this->render('recherche/inscription.twig', array('inscriptions'=>$inscription, 'nom'=>'Doe','prenom'=>'John'));   
    }

    #[Route('/Inscris', name: 'app_inscription')]
    public function afficheInscription(Request $request,ManagerRegistry $doctrine): Response
    {
        
        $form = $this->createForm(EmpInscriptionType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // Récupération de l'employé par son prénom et nom depuis la base de données
            $employeRepository = $this->getDoctrine()->getRepository(Employe::class);
            $employe = $employeRepository->findOneBy([
                'prenom' => $data['prenom'],
                'nom' => $data['nom'],
            ]);
            // Vérification si l'employé existe
            if ($employe) {
                // Récupération des inscriptions aux formations de l'employé
                $inscriptions = $employe->getFormations(); // Supposons que la relation soit nommée "formations" dans l'entité Employe
                
                // Rendu de la vue avec les inscriptions aux formations de l'employé
                return $this->render('recherche/inscription.html.twig', [
                    'form' => $form->createView(),
                    'inscriptions' => $inscriptions,
                ]);
            } else {
                // Si l'employé n'existe pas, affiche un message d'erreur
                $this->addFlash('error', 'Aucun employé trouvé avec ce prénom et ce nom.');
            }
        }

        // Rendu de la vue du formulaire
        return $this->render('recherche/empInscrit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
