<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    /**
     * controleur qui sert une page
     * contenant la liste de tous les produits
     */
    #[Route(path: '/admin', name: 'admin_menu')]
    public function admin():Response{


        //il faut retourner cette page
        return $this->render('admin/admin_menu.html.twig');
    }
}