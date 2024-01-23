<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapShowController extends AbstractController
{
    /**
     * controleur qui sert une page
     * contenant la liste de tous les produits
     */
    #[Route(path: '/map', name: 'show_map')]
    public function showmap():Response{

        $conn = new conn();
        $markers = $conn->index();
        //il faut retourner cette page
        return $this->render('map/map_view.html.twig', ['markers' => $markers]);
    }
}