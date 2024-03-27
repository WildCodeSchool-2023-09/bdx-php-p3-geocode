<?php

namespace App\Form;

use App\Entity\Opened;
use App\Entity\Terminal;
use App\Entity\Town;
use LongitudeOne\Spatial\DBAL\Types\Geometry\PointType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TerminalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude',
                'mapped' => false,
                'scale' => 4,
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer une longitude.",
                    ]),
                    new Assert\GreaterThanOrEqual([
                        'value' => -180,
                        'message' => 'La longitude doit être supérieure à -180'
                    ]),
                    new Assert\LessThanOrEqual([
                        'value' => 180,
                        'message' => 'La longitude doit être inférieure à 180'
                    ])
                ]
            ])
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude',
                'mapped' => false,
                'scale' => 4,
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer une latitude.",
                    ]),
                    new Assert\GreaterThanOrEqual([
                        'value' => -90,
                        'message' => 'La latitude doit être supérieure à -90'
                    ]),
                    new Assert\LessThanOrEqual([
                        'value' => 90,
                        'message' => 'La latitude doit être inférieure à 90'
                    ])
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'L\'adresse ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new NotBlank([
                        'message' => "Veuillez entrer une adresse.",
                    ]),
                ]
            ])
            ->add('outletType', ChoiceType::class, [
                'attr' => ['class' => 'terminal-select'],
                'label' => 'Type de prise',
                'choices' => [
                    'Type EF' => 'Type EF',
                    'Type 2' => 'Type 2',
                    'Type Combo CCS' => 'Type Combo CCS',
                    'Type CHAdeMO' => 'Type CHAdeMO',
                    'Autres' => 'Autres',
                    'inconnu' => 'inconnu',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez selectionner un type de prise.']),
                    new Choice([
                        'choices' => ['Type EF', 'Type 2', 'Type Combo CCS', 'Type CHAdeMO', 'Autres', 'inconnu'],
                        'message' => 'Veuillez selectionner un type de prise',
                    ]),
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('numberOutlet', NumberType::class, [
                'label' => 'Nombre de prises',
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer une latitude.",
                    ]),
                ]
            ])
            ->add('maxPower', NumberType::class, [
                'label' => 'Puissance maximum',
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer une latitude.",
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Terminal::class,
        ]);
    }
}
