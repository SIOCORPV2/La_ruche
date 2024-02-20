<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Marker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarkerControlleur extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route(path: '/admin/marker/create', name: 'marker_create')]
    public function create(Request $request):Response{

        $marker = new Marker();

        $form = $this->createFormBuilder($marker)
            ->add('region_id', TextType::class, [
                'attr' => ['class' => 'form-input']
            ])
            ->add('x_coord', HiddenType::class, [
                'attr' => ['id' => 'X_sliderValue', 'class' => 'form-input', 'value' => 50]
            ])
            ->add('y_coord', HiddenType::class, [
                'attr' => ['id' => 'Y_sliderValue', 'class' => 'form-input', 'value' => 50]
            ])
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-input']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create Marker',
                'attr' => ['class' => 'form-input']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set entity properties with form data
            $marker = $form->getData();

            try {
                // Persist and flush the entity
                $this->entityManager->persist($marker);
                $this->entityManager->flush();

                // Add a success flash message
                $this->addFlash('successCreate', 'Marker added successfully!');

                return $this->redirectToRoute('marker_create');
            } catch (\Exception $e) {
                // In case of an error, add a danger flash message
                $this->addFlash('dangerCreate', 'Error adding Marker: ' . $e->getMessage());
            }

        }

        //il faut retourner cette page
        return $this->render('admin/marker/marker_create.html.twig', [
            'form' => $form->createView(),
        ]);    }

    /**
     * Deletes a testing entity.
     *
     * @Route('/admin/marker/delete', name: 'marker_delete')
     * @Method("DELETE")
     */
    #[Route(path: '/admin/marker/delete', name: 'marker_delete')]
    public function delete(Request $request): Response
    {
        // Create a Marker object for the form
        $marker = new Marker();

        // Create the form
        $form = $this->createFormBuilder($marker)
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-input'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Delete Marker',
                'attr' => ['class' => 'form-input'],
            ])
            ->getForm();

        // Handle form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the name from the form
            $name = $form->get('name')->getData();

            // Find the entity by name
            $repository = $this->entityManager->getRepository(Marker::class);
            $marker = $repository->findOneBy(['name' => $name]);

            if ($marker) {
                try {
                    // Remove and flush the entity
                    $this->entityManager->remove($marker);
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

        // Fetch the markers from your connection class (assuming Connection class is used)
        $conn = new conn();
        $markers = $conn->index();

        // Return the rendered page
        return $this->render('admin/marker/marker_delete.html.twig', ['markers' => $markers, 'form' => $form->createView()]);
    }

    #[Route(path: '/admin/marker', name: 'marker_menu')]
    public function admin():Response{
        //il faut retourner cette page
        return $this->render('admin/marker/marker_menu.html.twig');
    }

    #[Route(path: '/admin/marker/select', name: 'marker_select')]
    public function select(Request $request):Response{



        // Fetch the markers from your connection class (assuming Connection class is used)
        $conn = new conn();
        $markersInfo = $conn->index();
        $marker = new Marker();
        // Create the form
        $form = $this->createFormBuilder($marker)
            ->add('name', ChoiceType::class, [
                'label' => 'Nom de la ville à modifier',
                'attr' => ['class' => 'form-input'],
                'choices' => $this->getMarkerChoices(),
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Choisir ce marqueur pour le modifier',
                'attr' => ['class' => 'form-input'],
            ])
            ->getForm();
        // Handle form submission
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Get the name from the form
            $name = $form->get('name')->getData();


            return $this->redirectToRoute('marker_update',['name' => $name]);

        }

        //il faut retourner cette page
        return $this->render('admin/marker/marker_select.html.twig', ['markers' => $markersInfo, 'form' => $form->createView()]);
    }

    #[Route(path: '/admin/marker/update/{name}', name: 'marker_update')]
    public function update(Request $request, string $name):Response{
        // Find the entity by name
        $marker = $this->entityManager->getRepository(Marker::class)->findOneBy(['name' => $name]);
        dump($marker);
        $xPos = $marker->getXCoord();
        $yPos = $marker->getYCoord();
        $nameMarker = $marker->getName();

        // Fetch the markers from your connection class (assuming Connection class is used)
        $conn = new conn();
        $markersInfo = $conn->index();
        // Create the form
        $form = $this->createFormBuilder($marker)
            ->add('region_id', TextType::class, ['attr' => ['value' => $marker->getRegionId()]])
            ->add('x_coord', HiddenType::class, ['attr' => ['value' => $marker->getXCoord()]])
            ->add('y_coord', HiddenType::class, ['attr' => ['value' => $marker->getYCoord()]])
            ->add('name', TextType::class, ['attr' => ['value' => $marker->getName()]])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour le marqueur',
            ])
            ->getForm();
        // Handle form submission
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $marker->setRegionId($form->get('region_id')->getData());
            $marker->setXCoord($form->get('x_coord')->getData());
            $marker->setYCoord($form->get('y_coord')->getData());
            $marker->setName($form->get('name')->getData());
            dump($form->getData());

            // Save the changes to the database
            $this->entityManager->flush();

            return $this->redirectToRoute('marker_select');

        }

        //il faut retourner cette page
        return $this->render('admin/marker/marker_update.html.twig', ['markers' => $markersInfo, 'form' => $form->createView(),'nameMarker' => $name,'xPos' => $xPos,'yPos' => $yPos]);
    }

    private function getMarkerChoices()
    {
        $markers = $this->entityManager->getRepository(Marker::class)->findAll();

        $choices = [];
        foreach ($markers as $marker) {
            $choices[$marker->getName()] = $marker->getName(); // You can change this as per your requirement
        }

        return $choices;
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