<?php


namespace App\Service;


use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Entity\User;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Panier
{
    protected $session;
    protected $produitRepository;
    protected $manager;

    public function __construct(SessionInterface $session, ProduitRepository $produitRepository, EntityManagerInterface $manager)
    {
        $this->session = $session;
        $this->produitRepository = $produitRepository;
        $this->manager = $manager;
    }

    public function creationDuPanier()
    {
        $panier = [
            'titre' => [],
            'id_produit' => [],
            'quantite' => [],
            'prix' => []
        ];

        return $panier;
    }

    public function add(string $titre, int $id_produit, int $quantite, float $prix)
    {

        $panier = $this->session->get('panier');

        if (empty($panier)) {

            $panier = $this->creationDuPanier();
            $this->session->set('panier', $panier);
        }

        // fonction prédéfinie en PHP array_search
        // retourne la position dans un tableau à a valeur recherché
        // Si la valeur n'existe pas dans le tableau il n'y a pas de valeur de retour
        // 2 arguements :
        // 1er : la valeur recherchée
        // 2 eme ; le tableau

        $position_produit = array_search($id_produit, $panier['id_produit']);

        if ($position_produit !== false) {
            $panier['quantite'][$position_produit] += $quantite;

            $this->session->set('panier', $panier);
        } else {
            $panier['titre'][] = $titre;
            $panier['id_produit'][] = $id_produit;
            $panier['quantite'][] = (int)$quantite;
            $panier['prix'][] = $prix;

            $this->session->set('panier', $panier);
        }
    }

    public function remove($id_produit): void
    {
        $panier = $this->session->get('panier');
        $position_produit = array_search($id_produit, $panier['id_produit']);

        if ($position_produit !== false) {
            //fonction predefinie PHP
            // array_splice
            //permet de supprimer une ou plkusieur lignes dans un tableau
            //3 arguemts:
            // 1er : le tableau
            //2e : a partir de quelle position
            //3e : nombre d'élément a supprimer

            array_splice($panier['titre'], $position_produit, 1);
            array_splice($panier['id_produit'], $position_produit, 1);
            array_splice($panier['quantite'], $position_produit, 1);
            array_splice($panier['prix'], $position_produit, 1);

            $this->session->set('panier', $panier);
        }
    }

    public function montantTotal()
    {
        $panier = $this->session->get('panier');
        $total = 0;

        if ($panier) {
            for ($i = 0, $iMax = count($panier['id_produit']); $i < $iMax; $i++) {
                if ($panier['quantite'][$i] != 0) {
                    $total += $panier["prix"][$i] * $panier["quantite"][$i];
                }
            }
            return round($total, 2);
        } else {
            throw new \ErrorException("error panier");
        }

    }

    public function vider()
    {
        $this->session->remove('panier');
    }

    public function verification()
    {
        $panier = $this->session->get('panier');

        for ($i = 0, $iMax = count($panier['id_produit']); $i < $iMax; $i++) {
            $produit = $this->produitRepository->find($panier['id_produit'][$i]);

            if ($produit->getStock() > 0 && ($produit->getStock() < $panier['quantite'][$i])) {
                $panier['quantite'][$i] = $produit->getStock();
            } elseif ($produit->getStock() === 0) {
                $panier['quantite'][$i] = 0;
            }
        }

        $this->session->set('panier', $panier);
        return $panier;
    }

    public function payer(User $user)
    {
        $panier = $this->verification();

        //appeler le panier dans la session
        // verifier le panier
        // Inserer la commande
        $commande = new Commande();
        $commande->setUser($user);
        $commande->setMontant($this->montantTotal());
        $commande->setDateAt(new \DateTime('now'));
        $commande->setEtat(0); // Etat 0 en cours de traitement  -  Etat 1 Expedier - Etat 2 livrer
        $this->manager->persist($commande);
        $this->manager->flush();


        for ($i = 0, $iMax = count($panier['id_produit']); $i < $iMax; $i++) {
            dump($panier['id_produit'][$i]);
            if ($panier['id_produit'][$i] > 0) {
                $produit = $this->produitRepository->find($panier['id_produit'][$i]);

                $detailCommande = new DetailCommande();

                $detailCommande->setCommande($commande);
                $detailCommande->setProduit($produit);
                $detailCommande->setPrix($panier['prix'][$i]);
                $detailCommande->setQuantite($panier['quantite'][$i]);

                $this->manager->persist($detailCommande);
                $this->manager->flush();

                $stockBDD = $produit->getStock();

                $newStock = $stockBDD - $panier['quantite'][$i];

                $produit->setStock($newStock);
                $this->manager->persist($detailCommande);
                $this->manager->flush();

                $this->remove($panier['id_produit'][$i]);
            }

        }


        // boucler les données pour les détails commande
        // supprimer
    }
}