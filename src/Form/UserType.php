<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserEdit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\AbstractComparison;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['create_user'] === true) {
            $builder
                ->add('email', TextType::class, [
                    "required" => false
                ])
//                ->add('password', TextType::class, [
//                    "required" => false,
//                    "label" => "mot de passe",
//                    'constraints' => [
//                        new NotBlank([
//                            'message' => "Veuillez saissir un mot de passe"
//                        ]),
//                        new EqualTo([
//                            'propertyPath' => "password",
//                            'message' => "Les mots de pass ne sont pas identique"
//                        ])
//                    ]
//                ])
//                //Autre solution: RepeatedType Field https://symfony.com/doc/current/reference/forms/types/repeated.html
//                ->add('confirmPassword', TextType::class, [
//                    "required" => false,
//                    "label" => "Confirmation du mot de passe",
//                    'constraints' => [
//                        new NotBlank([
//                            'message' => "Veuillez saissir un mot de passe"
//                        ]),
//                        new EqualTo([
//                            'propertyPath' => "confirmPassword",
//                            'message' => "Les mots de pass ne sont pas identique"
//                        ])
//                    ]
//                ])
                ->add('nom', TextType::class, [
                    "required" => false
                ])
                ->add('prenom', TextType::class, [
                    "required" => false
                ])
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => TextType::class,
//                    'mapped' => false,
                    "required" => false,
                    'first_name' => "first",
                    'first_options' => [
                        'label' => "Mot de passe"
                    ],
                    'second_name' => "second",
                    'second_options' => [
                        "label" => "Confirmation du mot de passe"
                    ],
                    'invalid_message' => "Les mots de passe doivent correspondre",
                    'constraints' => [
                        new NotBlank([
                            'message' => "Veuillez saissir un mot de passe"
                        ])
                    ]
                ));
        } else if ($options["edit"] === true) {
            $builder
                ->add('email', TextType::class, [
                    "required" => false
                ])
                ->add('nom', TextType::class, [
                    "required" => false
                ])
                ->add('prenom', TextType::class, [
                    "required" => false
                ]);
        } else if ($options["password"] === true) {
            $builder
                ->add('oldPassword', TextType::class, [
                    "required" => false,
                    "label" => "Ancien mot de passe"
                ])
                ->add('password', TextType::class, [
                    "required" => false,
                    "label" => "mot de passe",
                    'data' => ''
                ])
                //Autre solution: RepeatedType Field https://symfony.com/doc/current/reference/forms/types/repeated.html
                ->add('confirmPassword', TextType::class, [
                    "required" => false,
                    "label" => "Confirmation du mot de passe"
                ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'create_user' => false,
            'edit' => false,
            'password' => false
        ]);
    }
}
