<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Entity\UserEdit;
use App\Form\PasswordUpdateType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="profile")
     */
    public function edit(Request $request, EntityManagerInterface $manager, UserRepository $userRepository): Response
    {

//        $userSession = $this->getUser();
//        if ($userSession) {
//            $user = $userRepository->findOneBy(['id' => $userSession->getId()]);
//        } else {
//            throw new \ErrorException("no User Session");
//        }

//        if ($user) {
//            $form = $this->createForm(UserType::class, $user, array('edit' => true));
//            $user->getPassword();
//            $user->setConfirmPassword($user->getPassword());
//            $form->handleRequest($request);
//
//        } else {
//            throw new \ErrorException("no User");
//        }


        $user = $this->getUser();
        if($user) {
            $form = $this->createForm(UserType::class, $user, array('edit' => true));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager->persist($user);
                $manager->flush();

                $this->addFlash("success", $user->getPrenom() . ", votre compte a été modifié");
                return $this->redirectToRoute("profile");
            }

        } else {
            $user = null;
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            "formUser" => $form->createView()
        ]);
    }

    /**
     * @Route("/password", name="edit_password")
     */
    public function editPassword(Request $request, EntityManagerInterface $manager, UserRepository $userRepository, UserPasswordHasherInterface $encoder)
    {

        $user = $this->getUser();

//        $newPassword = new User();
        $form = $this->createForm(UserType::class, $user, array('password'=> true));
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$encoder->isPasswordValid($user, $user->getOldPassword()))
            {
                $form->get('oldPassword')->addError(new FormError("l'ancien mot de passe est incorrect"));
            }
            else // true
            {
                $hash = $encoder->hashPassword($user, $user->getPassword());

                $user->setPassword($hash);
                $manager->persist($user);
                $manager->flush();


                $this->addFlash("success", $user->getPrenom() . ", votre mot de passe a bien été modifié");

                return $this->redirectToRoute("profile");

            }
        }
        return $this->render('profile/edit_password.html.twig', [
            'user'=> $user,
            'formUser' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modification/password", name="password_modification")
     */
    public function password_modification(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder)
    {
        $user = $this->getUser();

        $passwordUpdate = new PasswordUpdate();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            // 1e étape : comparer oldPassword avec celui en BDD
            // 2e étape : vérifier que new et confirm soient identiques
            // =>> si on est dans la condition alors new et confirm sont identiques par les contraintes

            // si oldPassword ne correspond pas au password en BDD
            // la fonction prédéfinie password_verify() permet de comparer une string à un mdp hashé
            // il retourne un boolean
            // 2 arguments :
            // 1e: string
            // 2e: le mdp encodé


            //if(!password_verify($passwordUpdate->getOldPassword(), $user->getPassword() )) // false
            if(!$encoder->isPasswordValid($user, $passwordUpdate->getOldPassword()))
            {

                $form->get('oldPassword')->addError(new FormError("l'ancien mot de passe est incorrect"));
            }
            else // true
            {
                $hash = $encoder->hashPassword($user, $passwordUpdate->getNewPassword());

                $user->setPassword($hash);
                $manager->persist($user);
                $manager->flush();


                $this->addFlash("success", $user->getPrenom() . ", votre mot de passe a bien été modifié");

                return $this->redirectToRoute("profile");

            }
        }

        return $this->render('profile/password_modification.html.twig', [
            "formPassword" => $form->createView()
        ]);
    }
}
