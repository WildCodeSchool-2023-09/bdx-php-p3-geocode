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
            ])

            ->add('firstname', TextType::class, [
                'label' => 'Prenom',
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

//            ->add('currentPassword', PasswordType::class, [
//                'label' => 'Mot de passe actuel',
//                'mapped' => false,
//                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Please enter your current password',
//                    ]),
//                ],
//            ])
//            ->add('plainPassword', RepeatedType::class, [
//                'type' => PasswordType::class,
//                'first_options' => [
//                    'label' => 'Nouveau mot de passe',
//                ],
//                'second_options' => [
//                    'label' => 'Confirmation du nouveau mot de passe',
//                ],
//                'invalid_message' => 'Les mots de passe ne correspondent pas',
//                'mapped' => false,
//                'attr' => [
//                    'autocomplete' => 'new-password',
//                    'class' => 'input',
//                ],
//                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Please enter a new password',
//                    ]),
//                    new Length([
//                        'min' => 8,
//                        'minMessage' => 'Your password should be at least {{ limit }} characters',
//                        // max length allowed by Symfony for security reasons
//                        'max' => 4096,
//                    ]),
//                ],
//            ])

            ->add('pictureFile', VichFileType::class, [
                'label' => 'Ajouter une image',
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_uri' => false, // not mandatory, default is true
                'delete_label' => 'Supprimer', // Personnalisez l'étiquette de suppression
//                'download_label' => 'Télécharger', // Personnalisez l'étiquette de téléchargement
            ])

            ->add('picture', TextType::class, [
                'label' => 'Image actuelle',
                'required' => false,
                'attr' => [
                    'readonly' => true, // Vous pouvez ajouter cela pour le rendre en lecture seule
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
