<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Town;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
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
            ->add('birthday', DateType::class, [
                'label' => 'Anniversaire',
                'widget' => 'single_text'
//                'row_attr' => [
//                    'class' => 'liste'
//                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'input'
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre',
                'row_attr' => [
                    'class' => 'liste'
                ],
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'Femme',
                    'Non binaire' => 'non_binaire',
                    'Autre' => 'autre',
                ],
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'En m\'inscrivant à ce site je j\'accepte toutes les conditions  ',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                                ],
                                'row_attr' => [
                                    'class' => 'liste'
                                ],
                'attr' => [
                    'class' => 'input[type="checkbox"]'
                ],
            ])
//            ->add('plainPassword', PasswordType::class, [
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe'
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
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
