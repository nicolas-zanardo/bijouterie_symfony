<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Service\DateFr;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{


    /**
     * La fonction catalogue permet d'afficher tous les produits de la bijouterie (FRONT OFFICE)
     *
     * @Route("/catalogue", name="catalogue")
     */
    public function catalogue(ProduitRepository $repoProduit)
    {
        // lorsqu'on créé une entity, est généré en même temps son repository
        // repository : requête SELECT


        // 1e façon, création de l'objet issu de la class ProduitRepository
        // on appelle la méthode getDoctrine()provenant de la class AbstractController
        // suivi de la méthode getRepository() dans laquelle en argument doit être défini la class de l'entity


        //$repoProduit = $this->getDoctrine()->getRepository(Produit::class);
        //__________________________________________________________________________________________
        // 2e façon, c'est d'appeler en argument de la fonction catalogue la class suivi de son objet
        // ===> UNE DEPENDANCE


        $produitsArray = $repoProduit->findAll();
//        $produitsArray = $repoProduit->find(15); // Un seul item
//        $produitsArray= $repoProduit->findById(15); // retourne un tableau
//        $produitsArray = $repoProduit->findBy(["category" => [2,3], "prix" => 1235]);
//        $produitsArray = $repoProduit->findTout();
//        $produitsArray = $repoProduit->findPrix(15);
//        $produitsArray = $repoProduit->findPrixOrder("DESC");

//        $cat = [2,1];
//        $produitsArray = $repoProduit->findCategorie($cat);
//        $search = 1;
//        $produitsArray = $repoProduit->findSearch($search);

//        $search = "bague";
//        $produitsArray = $repoProduit->findObjt($search);

//        $min = 15;
//        $max = 50;
//        $produitsArray = $repoProduit->findBetween($min, $max);

//        dd($produitsArray);


        //dump($produitsArray); // s'affiche dans symfony profiler (en bas la nav noir, icône la cible)
        //dump($produitsArray);die;// s'affiche sur le navigateur car die tue la suite du code
        //dd($produitsArray); // dump die
        
        // par défaut dans le repository, il existe 4 fonctions
        // findAll() : sql : SELECT * FROM produit (sans argument)
        // find() : 1 argument, le champ id; sql : SELECT * FROM produit WHERE id = argument
        // findBy() : argument : tableau, définir le nom du champ et sa valeur, on peut en mettre plusieurs 
        // findBy(['prix' => 100, 'titre' => "bague"])


        return $this->render('produit/catalogue.html.twig', [
            "produits" => $produitsArray
        ]);
    }


    /**
     * la fonction fiche_produit() permet d'afficher les informations d'un produit
     * et elle a besoin d'un paramètre dans la route,
     * {id}
     *
     * @Route("fiche_produit/{id}", name="fiche_produit")
     */
    public function fiche_produit(Produit $produit, DateFr $dateFr)
    { //                        $id, ProduitRepository $repoProduit

        //dd($id);
        // $produit = $repoProduit->find($id);

        //dd($produit);

        $date_enregistrement = $produit->getDateAt();

        $moisReturn = $dateFr->moisFr($date_enregistrement->format('m'));

        $newDate = $date_enregistrement->format('d') . " " . $moisReturn . " " . $date_enregistrement->format("Y");

        $moisReturn2 = $dateFr->moisFr2($date_enregistrement->format('m'));
        $newDate2 = $date_enregistrement->format('d') . " " . $moisReturn2 . " " . $date_enregistrement->format("Y");


        return $this->render('produit/fiche_produit.html.twig', [
            "newDate4" => $dateFr->diplayDateFr("fr2", $date_enregistrement),
            "newDate3" => $dateFr->diplayDateFr("fr1", $date_enregistrement),
            "newDate2" => $newDate2,
            "newDate" => $newDate,
            "produit" => $produit
        ]);
    }


}
