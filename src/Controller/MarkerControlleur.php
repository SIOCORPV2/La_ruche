<?php

namespace App\Controller;

use App\Entity\Marker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('url', TextType::class, [
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


    #[Route(path: '/admin/marker/update', name: 'marker_update')]
    public function update(Request $request):Response{



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
            // Find the entity by name
            $repository = $this->entityManager->getRepository(Marker::class);
            $marker = $repository->findOneBy(['name' => $name]);

            $markerChosen = new Marker();
            $markerChosen->setXCoord($marker->getXCoord());
            $markerChosen->setYCoord($marker->getYCoord());
            $markerChosen->setRegionId($marker->getRegionId());
            $markerChosen->setName($marker->getName());
            $markerChosen->setUrl($marker->getUrl());
            if($marker){

                $marker = new Marker();

                $formUpdate = $this->createFormBuilder($marker)
                    ->add('region_id', TextType::class, [
                        'attr' => ['class' => 'form-input', 'value' => $markerChosen->getRegionId()]
                    ])
                    ->add('x_coord', HiddenType::class, [
                        'attr' => ['id' => 'X_sliderValue', 'class' => 'form-input', 'value' => $markerChosen->getXCoord()]
                    ])
                    ->add('y_coord', HiddenType::class, [
                        'attr' => ['id' => 'Y_sliderValue', 'class' => 'form-input', 'value' => $markerChosen->getYCoord()]
                    ])
                    ->add('name', TextType::class, [
                        'attr' => ['class' => 'form-input', 'value' => $markerChosen->getName()]
                    ])
                    ->add('url', TextType::class, [
                        'attr' => ['class' => 'form-input', 'value' => $markerChosen->getUrl()]
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'Mettre à jour le marqueur',
                        'attr' => ['class' => 'form-input']
                    ])
                    ->getForm();

                $formUpdate->handleRequest($request);

                if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
                    $existingMarker = $this->entityManager->getRepository(Marker::class)->findOneBy(['name' => $formUpdate->get('name')->getData()]);

                    if ($existingMarker) {
                        try {
                            // Update the existing entity with form values
                            if($formUpdate->get('x_coord') === null){

                                $existingMarker->setRegionId($formUpdate->get('region_id')->getData());
                                $existingMarker->setXCoord($formUpdate->get('x_coord')->getData());
                                $existingMarker->setYCoord($formUpdate->get('y_coord')->getData());
                                $existingMarker->setUrl($formUpdate->get('url')->getData());
                            }

                            // Persist changes to the database
                            $this->entityManager->flush();
                        } catch (\Exception $e) {
                            // In case of an error, add a danger flash message
                            $this->addFlash('dangerDelete', 'Error deleting Marker: ' . $e->getMessage());
                        }
                    }
                }

                return $this->render('admin/marker/marker_update.html.twig', ['formUpdate' => $formUpdate->createView(), 'markers' => $markersInfo, 'form' => $form->createView(),'markerChosen' => $markerChosen]);
            }
        }

        //il faut retourner cette page
        return $this->render('admin/marker/marker_update.html.twig', ['markers' => $markersInfo, 'form' => $form->createView()]);
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
}