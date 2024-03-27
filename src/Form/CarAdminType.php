<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', TextType::class, [
                'label' => 'Marque',
                'attr' => [
                    'class' => 'input'
                ],
                ])
            ->add('model', null, [
                'label' => 'Modèle',
                'attr' => [
                    'class' => 'input'
                ],
            ])
            ->add('outletType', ChoiceType::class, [
                'label' => 'Type de prise',
                'choices' => [
                    'Type EF' => 'Type 1',
                    'Type 2' => 'Type 2',
                    'Type Combo CCS' => 'Type 3',
                    'Type CHAdeMO' => 'Type 4',
                    'Autres' => 'Type 5',
                ],
            ])

            ->add('user', EntityType::class, [
                'label' => 'Id, Prénom et Nom de l\'utilisateur',
                'class' => User::class,
                'choice_label' => function ($entity) {
                    return $entity->getId() . ' - ' . $entity->getFirstname() . ' - ' . $entity->getLastname();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
