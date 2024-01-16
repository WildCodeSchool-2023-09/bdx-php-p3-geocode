<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', null, [
                'label' => 'Marque',
                'constraints' => [
                    new NotBlank(['message' => 'Merci de remplir le champ de la marque du véhicule']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le champ de la marque marque ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('model', null, [
                'label' => 'Modèle',
                'constraints' => [
                    new NotBlank(['message' => 'Merci de remplir le champ du modèle du véhicule']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le champ du modèle ne peut pas dépasser {{ limit }} caractères.',
                    ]),
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
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez selectionner un type de prise.']),
                    new Choice([
                        'choices' => ['Type 1', 'Type 2', 'Type 3', 'Type 4', 'Type 5'],
                        'message' => 'Veuillez selectionner un type de prise',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
