<?php

namespace App\Form;

use App\Entity\Events;
use App\Entity\Marker;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventCreateForm extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('id_marker', ChoiceType::class, [
                'label' => 'Nom de la ville à modifier',
                'attr' => ['class' => 'form-input'],
                'choices' => $this->getMarkers(),
            ])
            ->add('title', TextType::class)
            ->add('description', TextareaType::class,array('required' => false))
            ->add('location', TextareaType::class,array('required' => false))
            ->add('date', DateType::class,array('required' => false))
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }


    public function getMarkers()
    {
        $markers = $this->entityManager->getRepository(Marker::class)->findAll();

        $choices = [];
        foreach ($markers as $marker) {
            $choices[$marker->getName()] = $marker->getId(); // You can change this as per your requirement
        }

        return $choices;
    }

}