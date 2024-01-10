<?php

namespace App\Form;

use App\Entity\Town;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProfileUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer votre e-mail.",
                    ]),
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'input'
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => "Veuillez choisir un prénom d'utilisateur entre 2 et 255 caractères",
                        'maxMessage' => "Veuillez choisir un prénom d'utilisateur entre 2 et 255 caractères.",
                    ]),
                    new Regex([
                        'pattern' => '/^[a-z]+$/i',
                        'message' => "Prénom d'utilisateur invalide",
                        'htmlPattern' => '^[a-zA-Z]+$',
                    ]),
                    new NotBlank([
                        'message' => "Veuillez entrer votre prénom.",
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'input'
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => "Veuillez choisir un nom d'utilisateur entre 2 et 255 caractères",
                        'maxMessage' => "Veuillez choisir un nom d'utilisateur entre 2 et 255 caractères.",
                    ]),
                    new Regex([
                        'pattern' => '/^[a-z]+$/i',
                        'message' => "Nom d'utilisateur invalide",
                        'htmlPattern' => '^[a-zA-Z]+$',
                    ]),
                    new NotBlank([
                        'message' => "Veuillez entrer votre nom.",
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
