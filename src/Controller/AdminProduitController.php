<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\MatiereRepository;
use App\Repository\ProduitRepository;
use App\Service\DateFr;
use App\Service\ImagesUpload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * 🔴 Pour définir le path "/admin" sur toutes les routes de la class on va dans security.yaml :
 *  ❗ access_control:
 *           - { path: ^/admin, roles: ROLE_ADMIN }
 * @Route("/admin")
 */
class AdminProduitController extends AbstractController
{
    /*
        La class AdminProduitController contient les routes du CRUD du produit
        CRUD : CREATE (INSERT INTO) / READ (SELECT) / UPDATE / DELETE

        /gestion_produit/afficher     name="produit_afficher"
        /gestion_produit/ajouter      name="produit_ajouter"
        /gestion_produit/modifier     name="produit_modifier"
        /gestion_produit/supprimer    name="produit_supprimer"

    */


    /**
     * La fonction produit_afficher() permet d'afficher sous forme de tableau la liste des produits (BACK OFFICE)
     * On y trouvera le bouton pour ajouter un produit
     * Chaque ligne du tableau on trouvera les liens de modifier et de supprimer
     *
     * @Route("/gestion_produit/afficher", name="produit_afficher")
     */
    public function produit_afficher(ProduitRepository $repoProduit, MatiereRepository $matiereRepository,DateFr $dateFr)
    {
        $produitsArray = $repoProduit->findAll();
        $matiereArray = $matiereRepository->findAll();


        foreach ($produitsArray as $produit => $value) {
            $produitsArray[$produit] = [
                'bdd' =>$value,
                'newDateAt' => $dateFr->diplayDateFr("fr2", $value->getDateAt())
            ];
        }

        return $this->render('admin_produit/produit_afficher.html.twig', [
            "produits" => $produitsArray,
            "matieres" => $matiereArray
        ]);
    }


    /**
     * la fonction produit_ajouter() permet d'ajouter un produit
     * Cette route se trouve sur la route produit_afficher
     *
     * @Route("/gestion_produit/ajouter", name="produit_ajouter")
     * @throws \ErrorException
     */
    public function produit_ajouter(Request $request, EntityManagerInterface $manager, ImagesUpload $imagesUpload)
    {
        // Pour ajouter un produit on a besoin de créer une nouvelle instance issu de la class Produit
        $produit = new Produit;
        dump($produit); // on observe qu'on retrouve toutes les propriétés à valeur  null

        // Pour créer un formulaire, on utilise la méthode createForm()
        // 2 arguments obligatoires :
        // 1e : class du formType
        // 2e : objet issu de la class

        // 3e facultatif : tableau
        $form = $this->createForm(ProduitType::class, $produit, array('ajouter' => true));
        // $form est un objet (qui contient des méthodes)

        $form->handleRequest($request); // traitement du formulaire


        // si le formulaire a été soumis (submit) et validé (constraintes/ sécurité)

        if ($form->isSubmitted() && $form->isValid()) {
            $imagesUpload->insertImage($produit, $form);

//            $imageFile = $form->get('image')->getData();

            //dump($request->request);
            //dd($imageFile);


//            if ($imageFile) // si $imageFile n'est pas vide (une image a été upload)
//            {
                // 3 étapes pour le traitement de l'image


                // 1e : renommer l'image

//                $nomReelImage = str_replace(" ", "-", $imageFile->getClientOriginalName());
//                $nomImage = date("YmdHis") . "-" . uniqid('img_', true) . "-" . $nomReelImage;
                // création d'une class pour le traitement de l'image
                // 20210622121848-60d1b9081f50d-montre5.jpg
                //dd($nomImage);


                // 2e : Envoyer l'image dans le dossier public / images / imagesUpload

                // move() permet de déplacer un fichier
                // 2 arguments :
                // 1e : le placement : getParameter()
                // 2e : le nom qu'aura le fichier


                // getParameter() renvoit sur le fichier config/services.yaml
                // Paramater à définir
                // reprendre le nom : associer le chemin
                // %kernel.project_dir% c'est le projet
                // suivi du chemin : ex : public / images etc...

//                $imageFile->move(
//                    $this->getParameter("image_produit"),
//                    $nomImage
//                );


                // 3e étape : envoyer $nomImage en bdd

//                $produit->setImage($nomImage);
//
//
//            }


            $produit->setDateAt(new \DateTime('now'));
            dump($produit);

            $manager->persist($produit); // on persiste l'objet produit
            $manager->flush(); // on envoit l'objet produit en bdd


            // Notification : "le produit a bien été ajouté"
            // addFlash() est une méthode permettant de véhiculer sur le navigateur une notification
            // 2 arguments :
            // 1e : le nom du flash
            // 2e : le message

            $this->addFlash('success', "Le produit " . $produit->getTitre() . " N° " . $produit->getId() . " a bien été ajouté");


            // Redirection
            // méthode redirectToRoute()
            // 2 arguments
            // 1e OBLIGATOIRE : name de la route
            // 2e facultatif : c'est un tableau
            return $this->redirectToRoute("produit_afficher");


        }


        return $this->render('admin_produit/produit_ajouter.html.twig', [
            "formProduit" => $form->createView()
        ]);
    }

    /**
     * @Route("/gestion_produit/modifier/{id}", name="produit_modifier")
     * @throws \ErrorException
     */
    public function produit_modifier(Produit $produit, Request $request, EntityManagerInterface $manager, ImagesUpload $imagesUpload): Response
    {

        $form = $this->createForm(ProduitType::class, $produit, array('modifier' => true));

        $form->handleRequest($request); // Traitement du fomulaire

        if ($form->isSubmitted() && $form->isValid()) {
            $imagesUpload->editImage($produit, $form);
//            $imageFile = $form->get('imageFile')->getData();
//
//            if ($imageFile) {
//                $nomImage = date('YmdHis') . "-" . uniqid('img_', false) . "-" . $imageFile->getClientOriginalName();
//                $imageFile->move(
//                    $this->getParameter('image_produit'),
//                    $nomImage
//                );

                // unlink() êrmet de supprimer un fichier
                // 1 argument: chemin avec le nom fichier
//                if ($produit->getImage()) {
//                    unlink($this->getParameter('image_produit') . '/' . $produit->getImage());
//                }
//                $produit->setImage($nomImage);
//
//            }

//            if($request->request->get('imageQuestion') === "oui") {
//                unlink($this->getParameter('image_produit') . '/' . $produit->getImage());
//                $produit->setImage(NULL);
//            }
            $imagesUpload->deleteImage($request, $produit);
            // image null => image null *
            // image null => upload image  OK => supprimer l'ancienne
            // upload image => inchangé *
            // upload image => upload nouvelle image
            //upload image => image null

            $manager->persist($produit);
            $manager->flush();

            $this->addFlash('success', "Le produit " . $produit->getTitre() . " N° " . $produit->getId() . " a bien été modifié");
            return $this->redirectToRoute("produit_afficher");
        }


        return $this->render('admin_produit/produit_modifier.html.twig', [
            'produit' => $produit,
            "formProduit" => $form->createView()
        ]);
    }

    /**
     * @Route("/gestion_produit/delete_img/{id}", name="delete_img")
     */
    public function deleteImg(Produit $produit, EntityManagerInterface $manager) {

        unlink($this->getParameter('image_produit'). '/'.$produit->getImage());
        $produit->setImage(NULL);

        $manager->persist($produit);
        $manager->flush();

        $this->addFlash('success', "l'image du produit" . $produit->getTitre(). "a bien été supprimer");

        return $this->redirectToRoute("produit_modifier", ['id' => $produit->getId()]);
//        return $this->json("img delete", 200 );
    }


    /**
     * @Route("/gestion_produit/supprimer/{id}", name="produit_supprimer")
     */
    public function prosuit_supprimer(Produit $produit, EntityManagerInterface $manager) {
        // y'a t-il une image dans l'objet prosuit ?
        // condition
        //s'il y en a une : la supprimer du dossier imageUpload
        // persist($obj) // remove()
        // add flash
        // reditrectionToRoute => afficher

        if($produit->getImage()) {
            unlink($this->getParameter('image_produit'). '/'.$produit->getImage());
        }

        $titreProduit = $produit->getTitre();
        $idProduit = $produit->getId();

        $manager->remove($produit);
        $manager->flush();

        $this->addFlash('success', "le produit $titreProduit N° $idProduit a été supprimer");


        return $this->redirectToRoute("produit_afficher");


    }

}
