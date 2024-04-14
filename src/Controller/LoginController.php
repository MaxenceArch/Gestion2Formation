<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Employe;
use App\Form\ConnexionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Inscription;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Formation;



class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ConnexionType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
    
            $userRepository = $doctrine->getManager()->getRepository(Employe::class);
            $user = $userRepository->findOneBy(['login' => $data->getLogin()]);
    
            if ($user) {
                $mdpHash = MD5($data->getMdp().'15');
                
                if ($user->getMdp() == $mdpHash) {
                    $session->set('id', $user->getId());
                    $session->set('statut', $user->getStatut());
                    if ($user->getStatut() == 0) {
                        return $this->redirectToRoute('app_aff');
                    } elseif ($user->getStatut() == 1) {
                        return $this->redirectToRoute('app_affEmp');
                    }
                } else {
                    $this->addFlash('error', 'Mot de passe incorrect.');
                }
            } else {
                $this->addFlash('error', 'Nom d\'utilisateur incorrect.');
            }
        }
        return $this->render('login/editer.html.twig', ['controller_name' => 'LoginController', 'form' => $form->createView()]);
    }
    

}
