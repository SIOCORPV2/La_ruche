<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Marker;
use App\Form\EventCreateForm;
use App\Form\EventDeleteForm;
use App\Form\EventsType;
use App\Form\EventUpdateForm;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EventsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route(path: '/admin/events', name: 'events_menu')]
    public function index():Response{
        //il faut retourner cette page
        return $this->render('admin/events/events_menu.html.twig');
    }
    #[Route(path: '/admin/events/create', name: 'events_create')]
    public function create(Request $request):Response{

        $event = new Events();

        $form = $this->createForm(EventCreateForm::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($event);
            $this->entityManager->flush();

        }



        //il faut retourner cette page
        return $this->render('admin/events/events_create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route(path: '/admin/events/select', name: 'events_select')]
    public function select(Request $request):Response{

        $event = new Events();

        $form = $this->createFormBuilder($event)
            ->add('title', ChoiceType::class, [
                'label' => "Nom de l'event à modifier",
                'attr' => ['class' => 'form-input'],
                'choices' => $this->getEventChoices(),
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Choisir ce marqueur pour le modifier',
                'attr' => ['class' => 'form-input'],
            ])
            ->getForm();

        // Handle form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('events_update', ['title' => $form->get('title')->getData()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/events/events_select.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }


    #[Route('/admin/events/update/{title}', name: 'events_update', methods: ['GET', 'POST'])]
    public function edit(Request $request, Events $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventUpdateForm::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repository = $this->entityManager->getRepository(Events::class)->findOneBy(['title' => $form->get('title')->getData()]);


            $entityManager->flush();

            return $this->redirectToRoute('events_select');
        }

        return $this->render('admin/events/events_update.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route(path: '/admin/events/delete', name: 'events_delete')]
    public function delete(Request $request):Response{

        $event = new Events();


        $form = $this->createForm(EventDeleteForm::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the name from the form
            $title = $form->get('title')->getData();

            // Find the entity by name
            $repository = $this->entityManager->getRepository(Events::class);
            $event = $repository->findOneBy(['title' => $title]);

            if ($event) {
                try {
                    // Remove and flush the entity
                    $this->entityManager->remove($event);
                    $this->entityManager->flush();

                    // Add a success flash message
                    $this->addFlash('successDelete', 'Marker deleted successfully!');
                } catch (\Exception $e) {
                    // In case of an error, add a danger flash message
                    $this->addFlash('dangerDelete', 'Error deleting Marker: ' . $e->getMessage());
                }
            } else {
                // Add a danger flash message if the entity with the given name is not found
                $this->addFlash('dangerDelete', 'Marker with the specified name not found!');
            }
        }


        // Return the rendered page
        //il faut retourner cette page
        return $this->render('admin/events/events_delete.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/event/{name}', name: 'event_page')]
    public function show($name):Response{

        $repository = $this->entityManager->getRepository(Events::class);
        $event = $repository->findOneBy(['title' => $name]);


        //il faut retourner cette page
        return $this->render('events/show.html.twig', ["name" => $name, "event" => $event]);
    }

    #[Route(path: '/event/region/{id}', name: 'event_region')]
    public function showRegion($id):Response{

        $repository = $this->entityManager->getRepository(Events::class);
        $events = $repository->findAll();


        //il faut retourner cette page
        return $this->render('events/show.html.twig', ["id" => $id, "events" => $events]);
    }

    public function getEventChoices()
    {
        $events = $this->entityManager->getRepository(Events::class)->findAll();

        $choices = [];
        foreach ($events as $event) {
            $choices[$event->getTitle()] = $event->getTitle(); // You can change this as per your requirement
        }

        return $choices;
    }
}
