<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 🔴 Pour définir le path "/admin" sur toutes les routes de la class on va dans security.yaml :
 *  ❗ access_control:
 *           - { path: ^/admin, roles: ROLE_ADMIN }
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
