<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Formation;
use App\Form\ProduitType;
use App\Entity\Inscription;
use App\Entity\Employe;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use App\Form\SFType\SubmitType;

class ProduitController extends AbstractController
{
    #[Route('/ajoutProduit',name: 'app_ajout_produit')]
    public function ajoutProduit(Request $request,ManagerRegistry $doctrine, $produit = null)
    {
        if ($produit == null) {
            $produit = new Produit();
        }
        $form = $this->createForm(ProduitType::class, $produit);
        //récupération de la requête
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('app_aff');
        }
        return $this->render('produit/editer.html.twig',array('form'=>$form->createView()));
    }

    #[Route('/afficheProduit',name:'app_affProduit')]
    public function afficheLesProduit(ManagerRegistry $doctrine)
    {
        $produits = $doctrine->getManager()->getRepository(Produit::class)->findAll();
        
        if(!$produits){
            $message="Aucune inscriptions en attente";
        }
        else{
            $message=null;
        }
        return $this->render('produit/listeProduits.html.twig',array(
            'ensProduit'=>$produits,
            'message'=>$message));
    }
}