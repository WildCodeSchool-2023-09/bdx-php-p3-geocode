<?php

namespace App\Form;

use App\Entity\Town;
use App\Repository\TownRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class TownNameAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Town::class,
            'placeholder' => 'Votre ville',
            'choice_label' => 'namezipcode',
            'query_builder' => function (TownRepository $townRepository) {
                return $townRepository->createQueryBuilder('town');
            }
            //'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
