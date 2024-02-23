<?php

namespace App\Form;

use App\Entity\Town;
use App\Form\TownNameAutocompleteField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RouteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $minimumStep = 50;
        $maximumStep = 1000;
        $builder
            ->add('departure', TownNameAutocompleteField::class, [
                'class' => Town::class,
                'label' => 'Ville de départ',
                'attr' => [
                    'class' => 'input'
                ],
                'mapped' => false,
            ])
            ->add('arrival', TownNameAutocompleteField::class, [
                'class' => Town::class,
                'label' => 'Ville d\'arrivée',
                'attr' => [
                    'class' => 'input'
                ],
                'mapped' => false,
            ])
            ->add('step', NumberType::class, [
                'label' => 'Nombre de kilomètres entre chaque recharge',
                'mapped' => false,
                'constraints' => [
                    new Assert\GreaterThanOrEqual([
                        'value' => $minimumStep,
                        'message' => 'Les étapes doivent être au moins de ' . $minimumStep . ' kilomètres'
                    ]),
                    new Assert\LessThanOrEqual([
                        'value' => $maximumStep,
                        'message' => 'Les étapes doivent être au plus de ' . $maximumStep . ' kilomètres'
                    ])
                ]
            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' => [
                  'class' => 'button'
                ],
            ]);
    }
}
