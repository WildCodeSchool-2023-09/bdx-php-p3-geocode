<?php

namespace App\Form;

use App\Entity\Town;
use App\Form\TownNameAutocompleteField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RouteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
                'mapped' => false,
                'attr' => [
                'class' => 'input'
                ],
            ])
            ->add('step', NumberType::class, [
                'label' => 'Longueur des étapes',
                'mapped' => false
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                  'class' => 'button'
                ],
            ]);
    }
}
