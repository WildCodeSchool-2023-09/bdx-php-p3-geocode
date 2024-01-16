<?php

namespace App\Form;

use App\Entity\Town;
use App\Entity\TownSearched;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchTownType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('town', TownNameAutocompleteField::class, [
                'class' => Town::class,
                'label' => 'Ville',
                'attr' => [
                    'class' => 'input'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TownSearched::class,
        ]);
    }
}
