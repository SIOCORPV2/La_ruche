<?php

namespace App\Controller;

use App\Entity\Marker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
                'attr' => ['id' => 'X_sliderValue', 'class' => 'form-input', 'value' => 0]
            ])
            ->add('y_coord', HiddenType::class, [
                'attr' => ['id' => 'Y_sliderValue', 'class' => 'form-input', 'value' => 0]
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
                $this->addFlash('success', 'Marker added successfully!');

                return $this->redirectToRoute('marker_create');
            } catch (\Exception $e) {
                // In case of an error, add a danger flash message
                $this->addFlash('danger', 'Error adding Marker: ' . $e->getMessage());
            }

        }

        //il faut retourner cette page
        return $this->render('admin/marker/marker_create.html.twig', [
            'form' => $form->createView(),
        ]);    }

    #[Route(path: '/admin/marker/delete', name: 'marker_delete')]
    public function delete():Response{


        //il faut retourner cette page
        return $this->render('admin/marker/marker_delete.html.twig');
    }

    #[Route(path: '/admin/marker/update', name: 'marker_update')]
    public function update():Response{


        //il faut retourner cette page
        return $this->render('admin/marker/marker_update.html.twig');
    }
}