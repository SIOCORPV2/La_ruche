<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    #[Route(path: '/admin/events', name: 'events_menu')]
    public function index():Response{
        //il faut retourner cette page
        return $this->render('admin/events/events_menu.html.twig');
    }
    #[Route(path: '/admin/events/create', name: 'events_create')]
    public function create():Response{
        //il faut retourner cette page
        return $this->render('admin/events/events_menu.html.twig');
    }

    #[Route(path: '/admin/events/delete', name: 'events_delete')]
    public function delete():Response{
        //il faut retourner cette page
        return $this->render('admin/events/events_menu.html.twig');
    }

    #[Route(path: '/admin/events/update', name: 'events_update')]
    public function update():Response{
        //il faut retourner cette page
        return $this->render('admin/events/events_menu.html.twig');
    }


}