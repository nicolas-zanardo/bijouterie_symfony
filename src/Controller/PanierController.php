<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Service\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function panier(SessionInterface $session, Panier $panier): Response
    {
//        $panierPerso = $session->get('panier');
        //$session->remove('panier')
        $panierPerso = $panier->verification();
        dump($panierPerso);

        $total = $panier->montantTotal();
        return $this->render('panier/panier.html.twig', [
            "panier" => $panierPerso,
            'total' => $total
        ]);
    }

    /**
     * @Route("/panier/add", name="panier_add")
     */
    public function panier_add(Request $request, ProduitRepository $repoProduit, Panier $panier): Response
    {
        $quantite = $request->request->get('quantite');
        $id_produit = $request->request->get('id');

        $produit = $repoProduit->find($id_produit);

        $panier->add($produit->getTitre(), $id_produit, $quantite, $produit->getPrix());
        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/panier/remove/{id}", name="panier_remove")
     */
    public function panier_remove($id, Panier $panier)
    {
        $panier->remove($id);

        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/panier/vider", name="panier_vider")
     */
    public function panier_vider(Panier $panier) {
        $panier->vider();
        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/panier/paye", name="panier_payer")
     */
    public function panier_paye(Panier $panier) {

        $user = $this->getUser();
        $panier->payer($user);
        return $this->redirectToRoute("panier");
    }
}

