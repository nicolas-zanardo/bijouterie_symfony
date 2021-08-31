<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user, array('create_user' => true));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($encoder->hashPassword($user, $user->plainPassword));

            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", $user->getPrenom() . " a bien été crée");
            return $this->redirectToRoute("connexion");
        }

        return $this->render('security/inscription.html.twig', [
            "formUser" => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="connexion")
     */
    public function connexion()
    {
        return $this->render("security/connexion.html.twig");
    }

    /**
     * @Route("/deconnexion", name="deconnexion")
     */
    public function deconnexion()
    {
    }

    /**
     * La fonction roles() permet de checker le rôle de l'utlisateur qui vient de se connecter
     * et va permettre la redirection sur une route en fonction de son rôle
     * @Route("/roles", name="roles")
     */
    public function roles()
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute("dashboard");
        } elseif ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute("profile");
        }
    }
}
