<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route(path: '/', name: 'home')]
    public function index():Response{

        $conn = new conn();
        $markers = $conn->index();
        //il faut retourner cette page
        return $this->render('home/index.html.twig', ['markers' => $markers]);
    }
}