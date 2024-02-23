<?php
namespace App\Controller;

use App\Entity\Events;
use App\Entity\Marker;
use App\Entity\News;
use App\Form\EventCreateForm;
use App\Form\EventDeleteForm;
use App\Form\EventsType;
use App\Form\EventUpdateForm;
use App\Form\NewsCreateForm;
use App\Form\NewsDeleteForm;
use App\Form\NewsUpdateForm;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class NewsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route(path: '/admin/news', name: 'news_menu')]
    public function index(): Response
    {
        //il faut retourner cette page
        return $this->render('admin/news/news_menu.html.twig');
    }

    #[Route(path: '/admin/news/create', name: 'news_create')]
    public function create(Request $request): Response
    {

        $new = new News();

        $form = $this->createForm(NewsCreateForm::class, $new);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($new);
            $this->entityManager->flush();
            return $this->redirectToRoute("news_menu");

        }


        //il faut retourner cette page
        return $this->render('admin/news/news_create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route(path: '/admin/news/select', name: 'news_select')]
    public function select(Request $request): Response
    {

        $new = new News();

        $form = $this->createFormBuilder($new)
            ->add('title', ChoiceType::class, [
                'label' => "Nom de l'event à modifier",
                'attr' => ['class' => 'form-input'],
                'choices' => $this->getNewsChoices(),
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

            return $this->redirectToRoute('news_update', ['title' => $form->get('title')->getData()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/news/news_select.html.twig', [
            'new' => $new,
            'form' => $form,
        ]);
    }


    #[Route('/admin/news/update/{title}', name: 'news_update', methods: ['GET', 'POST'])]
    public function edit(Request $request, News $new, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NewsUpdateForm::class, $new);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repository = $this->entityManager->getRepository(News::class)->findOneBy(['title' => $form->get('title')->getData()]);


            $entityManager->flush();

            return $this->redirectToRoute('news_select');
        }

        return $this->render('admin/news/news_update.html.twig', [
            'new' => $new,
            'form' => $form,
        ]);
    }

    #[Route(path: '/admin/news/delete', name: 'news_delete')]
    public function delete(Request $request): Response
    {

        $new = new News();


        $form = $this->createForm(NewsDeleteForm::class, $new);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the name from the form
            $title = $form->get('title')->getData();

            // Find the entity by name
            $repository = $this->entityManager->getRepository(News::class);
            $new = $repository->findOneBy(['title' => $title]);

            if ($new) {
                try {
                    // Remove and flush the entity
                    $this->entityManager->remove($new);
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
            return $this->redirectToRoute("news_menu");
        }


        // Return the rendered page
        //il faut retourner cette page
        return $this->render('admin/news/news_delete.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/News/{name}', name: 'news_page')]
    public function show($name): Response
    {

        $repository = $this->entityManager->getRepository(News::class);
        $new = $repository->findOneBy(['title' => $name]);


        //il faut retourner cette page
        return $this->render('news/show.html.twig', ["name" => $name, "new" => $new]);
    }



    public function getNewsChoices()
    {
        $news = $this->entityManager->getRepository(News::class)->findAll();

        $choices = [];
        foreach ($news as $new) {
            $choices[$new->getTitle()] = $new->getTitle(); // You can change this as per your requirement
        }

        return $choices;
    }
}
