<?php

namespace App\Form;

use App\Entity\Town;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'input'
                ],
            ])

            ->add('roles', ChoiceType::class, [
                'label' => 'role',
                'choices' => [
                    'Visiteur' => '["ROLE_USER"]',
                    'Membre' => '["ROLE_CONTRIBUTOR"]',
                    'Administrateur' => '["ROLE_ADMIN"]',
                ],
                'expanded' => true,
            ])

            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'input'
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'input'
                ],
            ])
            ->add('birthday', DateType::class, [
                'label' => 'Anniversaire',
                'widget' => 'single_text',
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'Femme',
                    'Non binaire' => 'non_binaire',
                    'Ne pas spécifier' => 'non_spécifier',
                ],
            ])
//            ->add('picture')
//            ->add('updatedAt')

            ->add('town', TownNameAutocompleteField::class, [
                'class' => Town::class,
                'label' => 'Ville',
                'attr' => [
                    'class' => 'input'
                ],
            ]);
//        ;

        $builder->get('roles')->addModelTransformer(new CallbackTransformer(
            //transformer un tableau en une chaîne.
            function (array $arrayToString): ?string {
                return count($arrayToString) ? $arrayToString[0] : null;
            },
            //transformer une chaîne en tableau.
            function (string $strToArray): array {
                return ($strToArray) ? [$strToArray] : [];
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
